<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::guard('super_admin')->check()) {

            $hotels = Hotel::orderBy('id', 'DESC');

            if($request->ajax()) {
                return DataTables::of($hotels)
                ->addColumn('status', function($hotel){
                    if($hotel->status == 1){
                        return '<span class="badge rounded-pill border border-success text-success">Active</span>';
                    }else{
                        return '<span class="badge rounded-pill border border-danger text-danger">In-Active</span>';
                    }
                })
                ->addColumn('action', function($hotel){
                    return '<div class="dropdown">
                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="' . route('super_admins.hotels.edit', $hotel->id) . '" class="dropdown-item">
                                            <i class="fas fa-edit"></i>
                                                Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="' . route('super_admins.hotels.destroy', $hotel->id) . '" method="post" class="ajax-delete">'
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
            return view('super_admins.hotels.index');
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
        return view('super_admins.hotels.create');
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
            'hotel_name' => 'required|string|max:255',
            'hotel_unique_id' => 'required|string|max:127|unique:hotels,hotel_unique_id',
            'hotel_email' => 'required|email|max:255|unique:hotels,hotel_email',
            'hotel_image' => 'nullable|image|mimes:png,jpg,jpeg|max:5048',
            'address' => 'required',
            'division' => 'required',
            'district' => 'required',
            'upazila' => 'required',
            'post_code' => 'required|integer|gt:0',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $hotel = new Hotel();
        $hotel->hotel_unique_id = $request->hotel_unique_id;
        $hotel->hotel_name = $request->hotel_name;
        $hotel->hotel_email = $request->hotel_email;
        $hotel->address = $request->address;
        $hotel->description = $request->description;
        $hotel->division = $request->division;
        $hotel->district = $request->district;
        $hotel->upazila = $request->upazila;
        $hotel->post_code = $request->post_code;
        $hotel->status = $request->status;
        $hotel->phone_one = $request->phone_one;
        $hotel->phone_two = $request->phone_two;
        $hotel->mobile_one = $request->mobile_one;
        $hotel->mobile_two = $request->mobile_two;
        $hotel->approved_by = Auth::guard('super_admin')->user()->id;
        
        if ($request->hasFile('hotel_image')) {
            $image = $request->file('hotel_image');
            $ImageName = 'HOTEL_' .time() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/images/hotels/'), $ImageName);
            $hotel->hotel_image = 'public/uploads/images/hotels/' . $ImageName;
        }

        $hotel->save();

        DB::commit();
        
        // Cache::forget("hotel_$hotel->id");

        return redirect('super-admin/hotels')->with('success', 'Information has been added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('super_admins.hotels.edit', compact('hotel'));
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
            'hotel_name' => 'required|string|max:255',
            'hotel_unique_id' => 'required',
            'hotel_email' => [
                'required',
                'email',
                Rule::unique('hotels')->ignore($id),
            ],
            'hotel_image' => 'nullable|image|mimes:png,jpg,jpeg|max:5048',
            'address' => 'required',
            'division' => 'required',
            'district' => 'required',
            'upazila' => 'required',
            'post_code' => 'required|integer|gt:0',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $hotel = Hotel::findOrFail($id);
        $hotel->hotel_name = $request->hotel_name;
        $hotel->hotel_email = $request->hotel_email;
        $hotel->address = $request->address;
        $hotel->description = $request->description;
        $hotel->division = $request->division;
        $hotel->district = $request->district;
        $hotel->upazila = $request->upazila;
        $hotel->post_code = $request->post_code;
        $hotel->status = $request->status;
        $hotel->phone_one = $request->phone_one;
        $hotel->phone_two = $request->phone_two;
        $hotel->mobile_one = $request->mobile_one;
        $hotel->mobile_two = $request->mobile_two;

        $prevImageName = $hotel->hotel_image;

        if ($request->hasFile('hotel_image')) {
            $image = $request->file('hotel_image');
            $ImageName = 'HOTEL_' .time() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/images/hotels/'), $ImageName);
            $hotel->hotel_image = 'public/uploads/images/hotels/' . $ImageName;

            if($prevImageName != "public/default/hotel_image.png")
            {
                if(File::exists($prevImageName))
                    File::delete($prevImageName);
            }
        }

        $hotel->save();
        
        DB::commit();
        
        // Cache::forget("hotel_$hotel->id");

        return redirect('super-admin/hotels')->with('success', 'Information has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $image_path = $hotel->hotel_image;

        if($image_path != "public/default/hotel_image.png")
            if(File::exists($image_path))
                File::delete($image_path);

        $hotel->delete();

        if (!$request->ajax()) {
            return back()->with('success', 'Information has been deleted!');
        } else {
            return response()->json(['result' => 'success', 'message' => 'Information has been deleted sucessfully']);
        }
    }
}
