<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Admin::hasPermissionTo('staff.view', Auth::user()->role_id)){

            if(Auth::guard('admin')->check()) {

                $admins = Admin::where('hotel_id', Auth::user()->hotel_id)->orderBy('id', 'DESC')->get();
    
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
                                            <a href="' . route('admin.staffs.edit', $admin->id) . '" class="dropdown-item">
                                                <i class="fas fa-edit"></i>
                                                    Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="' . route('admin.staffs.destroy', $admin->id) . '" method="post" class="ajax-delete">'
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
                return view('admins.staffs.index');
            }
            abort(404);
        }else{
           abort(403, 'You are Unauthorized to Access!');
        } 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Admin::hasPermissionTo('staff.create', Auth::user()->role_id)){
            $roles = Role::whereNot('name', 'Admin')->get();
            return view("admins.staffs.create", compact('roles'));
        }else{
            abort(403, 'You are Unauthorized to Access!');
        }
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
            'role_id' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|email|max:255|unique:admins,email',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:5048',
            'status' => 'required'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $admin = new Admin();
        $admin->hotel_id = Auth::guard('admin')->user()->hotel_id;
        $admin->role_id = $request->role_id;
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->password = Hash::make($request->password);
        $admin->status = $request->status;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ImageName = 'STAFF_' .time() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/images/admins/'), $ImageName);
            $admin->image = 'public/uploads/images/admins/' . $ImageName;
        }
        
        $admin->save();

        DB::commit();
        
        // Cache::forget("admin_$admin->id");

        return redirect('admin/staffs')->with('success', 'Information has been added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Admin::hasPermissionTo('staff.edit', Auth::user()->role_id)) 
        {
            $admin = Admin::findOrFail($id);
            $roles = Role::whereNot('name', 'Admin')->get();
            return view("admins.staffs.edit", compact('admin', 'roles'));
        }
        else {
            abort(403, 'You are Unauthorized to Access!');
        }
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
        $admin->role_id = $request->role_id;
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->status = $request->status;

        $prevImagePath = $admin->image;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ImageName = 'STAFF_' .time() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/images/admins/'), $ImageName);
            $admin->image = 'public/uploads/images/admins/' . $ImageName;

            if($prevImagePath != "public/default/profile.png")
            {
                if(File::exists($prevImagePath))
                    File::delete($prevImagePath);
            }
        }
        
        $admin->save();

        DB::commit();
        
        // Cache::forget("admin_$admin->id");

        return redirect('admin/staffs')->with('success', 'Information has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(Admin::hasPermissionTo('staff.delete', Auth::user()->role_id)) 
        {
            $staff = Admin::findOrFail($id);
            $image_path = $staff->image;
    
            if($image_path != "public/default/profile.png")
                if(File::exists($image_path))
                    File::delete($image_path);
    
            $staff->delete();
    
            if (!$request->ajax()) {
                return back()->with('success', 'Information has been deleted!');
            } else {
                return response()->json(['result' => 'success', 'message' => 'Information has been deleted sucessfully']);
            }
        }
        else {
            abort(403, 'You are Unauthorized to Access!');
        } 
    }
}
