<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Hotel;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::guard('super_admin')->check()) {

            $admins = Admin::orderBy('id', 'DESC')->get();

            if($request->ajax()) {
                return DataTables::of($admins)
                ->addColumn('image', function($admin){
                    return '<img class="img-sm img-thumbnail" src="' . asset($admin->image) . '">';
                })
                ->addColumn('fullname', function($admin){
                    return $admin->full_name;
                })
                ->addColumn('status', function($admin){
                    if($admin->status == 1){
                        return '<span class="badge rounded-pill border border-success text-success">Active</span>';
                    }else{
                        return '<span class="badge rounded-pill border border-danger text-danger">In-Active</span>';
                    }
                })
                ->addColumn('action', function($admin){
                    return '<div class="dropdown">
                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="' . route('super_admin.admins.edit', $admin->id) . '" class="dropdown-item">
                                            <i class="fas fa-edit"></i>
                                                Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="' . route('super_admin.admins.destroy', $admin->id) . '" method="post" class="ajax-delete">'
                                            . csrf_field() 
                                            . method_field('DELETE') 
                                            . '<button type="button" class="btn-remove dropdown-item">
                                                    <i class="fas fa-trash-alt"></i>
                                                        Delete
                                                </button>
                                        </form>
                                    </li>
                                </ul>
                        </div>';
                })
                ->rawColumns(['image', 'fullname', 'status', 'action'])
                ->make(true);
            }
            return view('super_admins.admins.index');
        }
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hotels = Hotel::orderBy('id', 'DESC')->get();
        $roles = Role::orderBy('id', 'DESC')->get();
        return view("super_admins.admins.create", compact('hotels', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required',
            'role_id' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|email|max:255|unique:admins,email',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:5048',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $admin = new Admin();
        $admin->hotel_id = $request->hotel_id;
        $admin->role_id = $request->role_id;
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->password = Hash::make($request->password);
        $admin->status = $request->status;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ImageName = 'HOTEL_' .time() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/images/admins/'), $ImageName);
            $admin->image = 'public/uploads/images/admins/' . $ImageName;
        }
        
        $admin->save();

        DB::commit();
        
        // Cache::forget("admin_$admin->id");

        return redirect('super-admin/admins')->with('success', 'Information has been added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $hotels = Hotel::orderBy('id', 'DESC')->get();
        $roles = Role::orderBy('id', 'DESC')->get();
        return view("super_admins.admins.edit", compact('admin', 'hotels', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required',
            'role_id' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins')->ignore($id),
            ],
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:5048',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $admin = Admin::findOrFail($id);
        $admin->hotel_id = $request->hotel_id;
        $admin->role_id = $request->role_id;
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->status = $request->status;

        $prevImageName = $admin->image;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ImageName = 'ADMIN_' .time() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/images/admins/'), $ImageName);
            $admin->image = 'public/uploads/images/admins/' . $ImageName;

            if($prevImageName != "public/default/profile.png")
            {
                if(File::exists($prevImageName))
                    File::delete($prevImageName);
            }
        }
        
        $admin->save();

        DB::commit();
        
        // Cache::forget("admin_$admin->id");

        return redirect('super-admin/admins')->with('success', 'Information has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $image_path = $admin->image;

        if($image_path != "public/default/profile.png")
            if(File::exists($image_path))
                File::delete($image_path);

        $admin->delete();

        if (!$request->ajax()) {
            return back()->with('success', 'Information has been deleted!');
        } else {
            return response()->json(['result' => 'success', 'message' => 'Information has been deleted sucessfully']);
        }
    }
}
