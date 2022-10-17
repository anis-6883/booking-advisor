@extends('super_admins.layouts.app')

@section('page_title', 'Edit Hotel')

@section('super_admins_content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}"><i class='fas fa-home text-muted'></i></a></li>
        <li class="breadcrumb-item"> <a class="text-muted" href="{{ route('super_admin.hotels.index') }}">Hotel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav>
<div class="row">
<div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow-1">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Edit <span style="color: #0C32DC;">Hotel</span></h3>
                    <hr>
                    <form action="{{ route('super_admin.hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Hotel Name</label>
                                    <input type="text" class="form-control" name="hotel_name" value="{{ $hotel->hotel_name }}" required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control @error('hotel_email') is-invalid @enderror" name="hotel_email" value="{{ $hotel->hotel_email }}" required>
                                    @error('hotel_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Hotel Unique ID</label>
                                    <input type="text" name="hotel_unique_id" class="form-control" value="{{ $hotel->hotel_unique_id }}" readonly required>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Post Code</label>
                                    <input type="number" min="1" class="form-control" name="post_code" value="{{ $hotel->post_code }}" required>
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label class="form-label">Division</label>
                                    <select data-selected="{{ $hotel->division }}" class="form-select select2" name="division" id="division_options" onchange="divisionsList();" required>
                                        <option selected value="">Select Division</option>
                                        <option value="Barishal">Barishal</option>
                                        <option value="Chattogram">Chattogram</option>
                                        <option value="Dhaka">Dhaka</option>
                                        <option value="Khulna">Khulna</option>
                                        <option value="Mymensingh">Mymensingh</option>
                                        <option value="Rajshahi">Rajshahi</option>
                                        <option value="Rangpur">Rangpur</option>
                                        <option value="Sylhet">Sylhet</option>
                                    </select>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label class="form-label">District</label>
                                    <select data-selected="{{ $hotel->district }}" class="form-select select2" name="district" id="district_options" onchange="thanaList();" required>
                                        <option selected value="">Select Division</option>
                                    </select>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label class="form-label">Upazila</label>
                                    <select data-selected="{{ $hotel->upazila }}" class="form-select select2" name="upazila" id="upazila_options" required><option disabled selected>Select District</option></select>
                                </div>
                            </div><!-- Col -->
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" class="form-control dropify" name="hotel_image" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" data-default-file="{{ asset($hotel->hotel_image) }}">
                                </div>
                            </div><!-- Col -->
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" rows="6" name="address" required>{{ $hotel->address }}</textarea>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="6" name="description">{{ $hotel->description }}</textarea>
                                </div>
                            </div><!-- Col -->
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="mb-3">
                                    <label class="form-label">Phone 1</label>
                                    <input type="text" class="form-control" name="phone_one" value="{{ $hotel->phone_one }}">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-3">
                                <div class="mb-3">
                                    <label class="form-label">Phone 2</label>
                                    <input type="text" class="form-control" name="phone_two" value="{{ $hotel->phone_two }}">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-3">
                                <div class="mb-3">
                                    <label class="form-label">Mobile 1</label>
                                    <input type="text" class="form-control" name="mobile_one" value="{{ $hotel->mobile_one }}">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-3">
                                <div class="mb-3">
                                    <label class="form-label">Mobile 2</label>
                                    <input type="text" class="form-control" name="mobile_two" value="{{ $hotel->mobile_two }}">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select select2" name="status" required data-selected="{{ $hotel->status }}">
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-12 mb-4">
                                <div class="mt-2 d-flex justify-content-end" id="submit-trigger">
                                    <button type="submit" class="btn btn-primary submit">Update <i class="fas fa-angle-double-right"></i></button>
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- row -->
@endsection

@section('js-script')
    <script src="{{ asset('public/backend/js/district_list.js') }}"></script>
    <script src="{{ asset('public/backend/js/upazila-list.js') }}"></script>
    <script>
        $('select[name=division]').on('change', function(){
            $('select[name=upazila]').html('<option value="">Select District</option>');
        })
    </script>
@endsection