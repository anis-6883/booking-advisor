<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Facility;
use App\Models\RoomFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RoomFacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Admin::hasPermissionTo('facility.view', Auth::user()->role_id)){

            if(Auth::guard('admin')->check()) {

                $facilities = Facility::where('hotel_id', Auth::user()->hotel_id)->orderBy('id', 'DESC')->get();
    
                if($request->ajax()) {
                    return DataTables::of($facilities)
                    ->addColumn('status', function($facility){
                        if($facility->status == 1){
                            return '<span class="badge rounded-pill border border-success text-success">Active</span>';
                        }else{
                            return '<span class="badge rounded-pill border border-danger text-danger">In-Active</span>';
                        }
                    })
                    ->addColumn('action', function($facility){
                        return '<div class="dropdown">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="' . route('admin.room-facilities.edit', $facility->id) . '" class="dropdown-item">
                                                <i class="fas fa-edit"></i>
                                                    Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="' . route('admin.room-facilities.destroy', $facility->id) . '" method="post" class="ajax-delete">'
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
                return view('admins.facilities.index');
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
        if(Admin::hasPermissionTo('facility.create', Auth::user()->role_id)){
            return view("admins.facilities.create");
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
            'facility_name' => [
                'required', 
                Rule::unique('facilities')
                    ->where('hotel_id', Auth::guard('admin')->user()->hotel_id)
            ],
            'status' => 'required'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $facility = new Facility();
        $facility->hotel_id = Auth::guard('admin')->user()->hotel_id;
        $facility->facility_name = $request->facility_name;
        $facility->status = $request->status;
        $facility->save();

        DB::commit();

        return redirect('admin/room-settings/room-facilities')->with('success', 'Information has been added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Admin::hasPermissionTo('facility.edit', Auth::user()->role_id)){
            $facility = Facility::findOrFail($id);
            return view("admins.facilities.edit", compact('facility'));
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
            'facility_name' => [
                'required', 
                Rule::unique('facilities')
                    ->where('hotel_id', Auth::guard('admin')->user()->hotel_id)
                    ->ignore($id)
                ],
            'status' => 'required'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $facility = Facility::findOrFail($id);
        $facility->facility_name = $request->facility_name;
        $facility->status = $request->status;
        $facility->save();

        DB::commit();

        return redirect('admin/room-settings/room-facilities')->with('success', 'Information has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(Admin::hasPermissionTo('facility.delete', Auth::user()->role_id)) 
        {
            $facility = Facility::findOrFail($id);
            $facility->delete();

            RoomFacility::where('facility_id', $id)->delete();
    
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
