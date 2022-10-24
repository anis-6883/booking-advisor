<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BedType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class BedTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Admin::hasPermissionTo('bed.type.view', Auth::user()->role_id)){

            if(Auth::guard('admin')->check()) {

                $bed_types = BedType::where('hotel_id', Auth::user()->hotel_id)->orderBy('id', 'DESC')->get();
    
                if($request->ajax()) {
                    return DataTables::of($bed_types)
                    ->addColumn('status', function($bed_type){
                        if($bed_type->status == 1){
                            return '<span class="badge rounded-pill border border-success text-success">Active</span>';
                        }else{
                            return '<span class="badge rounded-pill border border-danger text-danger">In-Active</span>';
                        }
                    })
                    ->addColumn('action', function($bed_type){
                        return '<div class="dropdown">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="' . route('admin.bed-types.edit', $bed_type->id) . '" class="dropdown-item">
                                                <i class="fas fa-edit"></i>
                                                    Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="' . route('admin.bed-types.destroy', $bed_type->id) . '" method="post" class="ajax-delete">'
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
                    ->rawColumns(['status', 'action'])
                    ->make(true);
                }
                return view('admins.bed_types.index');
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
        if(Admin::hasPermissionTo('bed.type.create', Auth::user()->role_id)){
            return view("admins.bed_types.create");
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
            'bed_type_name' => [
                'required', 
                Rule::unique('bed_types')
                    ->where('hotel_id', Auth::guard('admin')->user()->hotel_id)
            ],
            'status' => 'required'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $bed_type = new BedType();
        $bed_type->hotel_id = Auth::guard('admin')->user()->hotel_id;
        $bed_type->bed_type_name = $request->bed_type_name;
        $bed_type->status = $request->status;
        $bed_type->save();

        DB::commit();

        return redirect('admin/room-settings/bed-types')->with('success', 'Information has been added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Admin::hasPermissionTo('bed.type.edit', Auth::user()->role_id)){
            $bed_type = BedType::findOrFail($id);
            return view("admins.bed_types.edit", compact('bed_type'));
        }else{
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
            'bed_type_name' => [
                'required', 
                Rule::unique('bed_types')
                    ->where('hotel_id', Auth::guard('admin')->user()->hotel_id)
                    ->ignore($id)
                ],
            'status' => 'required'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $bed_type = BedType::findOrFail($id);
        $bed_type->bed_type_name = $request->bed_type_name;
        $bed_type->status = $request->status;
        $bed_type->save();

        DB::commit();

        return redirect('admin/room-settings/bed-types')->with('success', 'Information has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(Admin::hasPermissionTo('bed.type.delete', Auth::user()->role_id)) 
        {
            $bed_type = BedType::findOrFail($id);
            $bed_type->delete();
    
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
