<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BedType;
use App\Models\Facility;
use App\Models\Room;
use App\Models\RoomFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Admin::hasPermissionTo('room.view', Auth::user()->role_id)){

            if(Auth::guard('admin')->check()) {

                $rooms = Room::where('hotel_id', Auth::user()->hotel_id)->orderBy('id', 'DESC')->get();
    
                if($request->ajax()) {
                    return DataTables::of($rooms)
                    ->addColumn('status', function($room){
                        if($room->status == 1){
                            return '<span class="badge rounded-pill border border-success text-success">Active</span>';
                        }else{
                            return '<span class="badge rounded-pill border border-danger text-danger">In-Active</span>';
                        }
                    })
                    ->addColumn('action', function($room){
                        return '<div class="dropdown">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="' . route('admin.rooms.edit', $room->id) . '" class="dropdown-item">
                                                <i class="fas fa-edit"></i>
                                                    Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="' . route('admin.rooms.destroy', $room->id) . '" method="post" class="ajax-delete">'
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
                return view('admins.rooms.index');
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
        if(Admin::hasPermissionTo('room.create', Auth::user()->role_id)){
            $bed_types = BedType::where('hotel_id', Auth::user()->hotel_id)->orderBy('id', 'DESC')->get();
            $facilities = Facility::where('hotel_id', Auth::user()->hotel_id)->orderBy('id', 'DESC')->get();
            return view("admins.rooms.create", compact('bed_types', 'facilities'));
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
            'room_title' => 'required|string|max:255',
            'bed_type_id' => 'required',
            'master_image' => 'image|mimes:png,jpg,jpeg|max:5048',
            'regular_price' => 'required|integer',
            'total_room' => 'required|integer',
            'capacity' => 'required',
            'discounted_pct' => 'nullable|integer|gt:0|lte:100',
            'discount_start_date' => 'required_with:discounted_pct',
            'discount_end_date' => 'required_with:discounted_pct',
            'status' => 'required',
            'facilities' => 'required',
            'facilities.*' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $room = new Room();
        $room->hotel_id = Auth::guard('admin')->user()->hotel_id;
        $room->room_title = $request->room_title;
        $room->bed_type_id = $request->bed_type_id;
        $room->regular_price = $request->regular_price;
        $room->total_room = $request->total_room;
        $room->capacity = $request->capacity;
        $room->discounted_pct = $request->discounted_pct;
        $room->discount_start_date = is_null($request->discounted_pct) ? null : $request->discount_start_date;
        $room->discount_end_date = is_null($request->discounted_pct) ? null : $request->discount_end_date;
        $room->square_meter = $request->square_meter;
        $room->tax_price = $request->tax_price;
        $room->status = $request->status;
        
        if ($request->hasFile('master_image')) {
            $image = $request->file('master_image');
            $ImageName = 'ROOM_' . time() . '_' . rand() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/images/rooms/'), $ImageName);
            $room->master_image = 'public/uploads/images/rooms/' . $ImageName;
        }

        $room->save();

        for ($i=0; $i < count($request->facilities); $i++) { 
            $room_facility = new RoomFacility();
            $room_facility->room_id = $room->id;
            $room_facility->facility_id = $request->facilities[$i];
            $room_facility->save();
        }

        DB::commit();

        return redirect('admin/room-settings/rooms')->with('success', 'Information has been added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Admin::hasPermissionTo('room.edit', Auth::user()->role_id)) 
        {
            $bed_types = BedType::where('hotel_id', Auth::user()->hotel_id)->orderBy('id', 'DESC')->get();
            $room_facilities = RoomFacility::where('room_id', $id)->pluck('facility_id');
            $facilities = Facility::where('hotel_id', Auth::user()->hotel_id)->orderBy('id', 'DESC')->get();
            $room = Room::findOrFail($id);

            return view("admins.rooms.edit", compact('bed_types', 'facilities', 'room', 'room_facilities'));
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
            'room_title' => 'required|string|max:255',
            'bed_type_id' => 'required',
            'master_image' => 'image|mimes:png,jpg,jpeg|max:5048',
            'regular_price' => 'required|integer',
            'total_room' => 'required|integer',
            'capacity' => 'required',
            'discounted_pct' => 'nullable|integer|gt:0|lte:100',
            'discount_start_date' => 'required_with:discounted_pct',
            'discount_end_date' => 'required_with:discounted_pct',
            'status' => 'required',
            'facilities' => 'required',
            'facilities.*' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $room = Room::findOrFail($id);
        $room->room_title = $request->room_title;
        $room->bed_type_id = $request->bed_type_id;
        $room->regular_price = $request->regular_price;
        $room->total_room = $request->total_room;
        $room->capacity = $request->capacity;
        $room->discounted_pct = $request->discounted_pct;
        $room->discount_start_date = is_null($request->discounted_pct) ? null : $request->discount_start_date;
        $room->discount_end_date = is_null($request->discounted_pct) ? null : $request->discount_end_date;
        $room->square_meter = $request->square_meter;
        $room->tax_price = $request->tax_price;
        $room->status = $request->status;

        $prevImagePath = $room->master_image;
        
        if ($request->hasFile('master_image')) {
            $image = $request->file('master_image');
            $ImageName = 'ROOM_' . time() . '_' . rand() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/images/rooms/'), $ImageName);
            $room->master_image = 'public/uploads/images/rooms/' . $ImageName;

            if(File::exists($prevImagePath))
                File::delete($prevImagePath);
    }

        $room->save();

        RoomFacility::where('room_id', $id)->delete();

        for ($i=0; $i < count($request->facilities); $i++) { 
            $room_facility = new RoomFacility();
            $room_facility->room_id = $room->id;
            $room_facility->facility_id = $request->facilities[$i];
            $room_facility->save();
        }

        DB::commit();

        return redirect('admin/room-settings/rooms')->with('success', 'Information has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(Admin::hasPermissionTo('room.delete', Auth::user()->role_id)) 
        {
            $room = Room::findOrFail($id);
            $image_path = $room->master_image;
    
            if(File::exists($image_path))
                File::delete($image_path);

            RoomFacility::where('room_id', $id)->delete();
    
            $room->delete();
    
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
