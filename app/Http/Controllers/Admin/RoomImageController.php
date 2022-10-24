<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoomImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Admin::hasPermissionTo('room.images.view', Auth::user()->role_id)){

            if(Auth::guard('admin')->check()) {

                $rooms = Room::where('hotel_id', Auth::user()->hotel_id)->orderBy('id', 'DESC')->get();
    
                if($request->ajax()) {
                    return DataTables::of($rooms)
                    ->addColumn('images', function($room){
                        $room_images = RoomImage::where('room_id', $room->id)->get();

                        if(count($room_images) > 0){
                            $images = "";
                            foreach ($room_images as $image) {
                                $images .= '<img style="border-radius: 0;" src="' . asset($image->image_file) . '" alt="Room Image" width="80px" height="80px">';
                            }
                            return $images;
                        }else{
                            return '<img style="border-radius: 0;" src="' . asset('public/default/no-image.png') . '" alt="Room Image" width="80px" height="80px">';
                        }
                    })
                    ->addColumn('add_images', function($room){
                      return '<form action="' . route('admin.room-images.store') . '" method="POST" enctype="multipart/form-data">
                      '. csrf_field() .'
                        <input 
                            type="file" 
                            name="room_images[]" 
                            class="form-control input-default" 
                            multiple required>
                        <input type="hidden" name="room_id" value="' . $room->id . '">
                        <button type="submit" class="btn btn-xs btn-primary m-2">Submit</button>
                        <button type="reset" class="btn btn-xs btn-warning">Reset</button>    
                        </form>';
                    })
                    ->addColumn('action', function($room){
                        return '<div class="dropdown">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="' . route('admin.room-images.edit', $room->id) . '" class="dropdown-item">
                                                <i class="fas fa-edit"></i>
                                                    Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="' . route('admin.room.images.destroy.all') . '" method="post">'
                                                . csrf_field() 
                                                . method_field('DELETE') 
                                                . '<button type="button" class="btn-remove dropdown-item">
                                                        <i class="fas fa-trash-alt"></i>
                                                            Delete All
                                                    </button>
                                                    <input type="hidden" name="room_id" value="'. $room->id .'">
                                            </form>
                                        </li>
                                    </ul>
                            </div>';
                    })
                    ->rawColumns(['images', 'add_images', 'action'])
                    ->make(true);
                }
                return view('admins.room_images.index');
            }
            abort(404);
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
        if(Admin::hasPermissionTo('room.images.create', Auth::user()->role_id))
        {
            $room_images = $request->file('room_images');
            $room_id = $request->room_id;

            $validator = Validator::make($request->all(), [
                'room_images' => 'required',
                'room_images.*' => 'mimes:jpeg,jpg,png|max:5120'
            ]);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();
            
            foreach($room_images as $image)
            {
                $room_image = new RoomImage();
                $ImageName = 'ROOM_' . uniqid() . '_' . rand() . '.' . $image->getClientOriginalExtension();
                $image->move(base_path('public/uploads/images/rooms/'), $ImageName);
                $room_image->room_id = $room_id;
                $room_image->image_file = 'public/uploads/images/rooms/' . $ImageName;
                $room_image->save();
            }

            DB::commit();

            return redirect('admin/room-settings/room-images')->with('success', 'Information has been added!');
        }
        else{
            abort(403, 'You are Unauthorized to Access!');
         } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Admin::hasPermissionTo('room.images.edit', Auth::user()->role_id)) 
        {
            $room_images = RoomImage::where('room_id', $id)->get();
            if(count($room_images) > 0){
                return view("admins.room_images.edit", compact('room_images'));
            }else{
                return back()->with('warning', 'No Images Found!');
            }
        }
        else {
            abort(403, 'You are Unauthorized to Access!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Admin::hasPermissionTo('room.images.delete', Auth::user()->role_id)) 
        {
            $image = RoomImage::findOrFail($id);
            $image_path = $image->image_file;
    
            if(File::exists($image_path))
                File::delete($image_path);

            $image->delete();
            return redirect('admin/room-settings/room-images')->with('success', 'Information has been deleted!');
        }
        else {
            abort(403, 'You are Unauthorized to Access!');
        }
    }

    public function destroyAll(Request $request)
    {
        if(Admin::hasPermissionTo('room.images.delete', Auth::user()->role_id)) 
        {
            $room_images = RoomImage::where('room_id', $request->room_id)->get();

            if(count($room_images) > 0)
            {
                foreach ($room_images as $image) 
                {
                    $image_path = $image->image_file;
        
                    if(File::exists($image_path))
                        File::delete($image_path);

                    $image->delete();
                }
            }
            else{
                return back()->with('warning', 'No Images Found!');
            }

            return back()->with('success', 'Information has been deleted!');
         
        }
        else {
            abort(403, 'You are Unauthorized to Access!');
        } 
    }
}
