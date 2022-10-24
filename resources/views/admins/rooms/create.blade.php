@extends('admins.layouts.app')

@section('page_title', 'Add Room')

@section('admins_content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class='fas fa-home text-muted'></i></a></li>
        <li class="breadcrumb-item"> <a class="text-muted" href="{{ route('admin.rooms.index') }}">Room</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create</li>
    </ol>
</nav>
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Add New <span style="color: #0C32DC;">Room</span></h3>
                        <hr>
                        <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            @method('POST')

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Room Title</label>
                                        <input type="text" class="form-control" name="room_title" value="{{ old('room_title') }}" required>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bed Type</label>
                                        <select class="form-select select2" name="bed_type_id" required>
                                            <option selected value="">Select Bed</option>
                                            @foreach ($bed_types as $type)
                                                <option value="{{ $type->id }}">{{ $type->bed_type_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Master Image</label>
                                        <input type="file" class="form-control dropify" name="master_image" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" required>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Regular Price</label>
                                        <input type="number" class="form-control" name="regular_price" value="{{ old('regular_price') }}" required>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Total Room</label>
                                        <input type="number" class="form-control" name="total_room" value="{{ old('total_room') }}" required>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Human Capacity</label>
                                        <input type="number" class="form-control" name="capacity" value="{{ old('capacity') }}" required>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Square Meter</label>
                                        <input type="number" class="form-control" name="square_meter" value="{{ old('square_meter') }}">
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tax Price</label>
                                        <input type="number" class="form-control" name="tax_price" value="{{ old('tax_price') }}">
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Discount (in percentage)</label>
                                        <input type="number" class="form-control" name="discounted_pct" value="{{ old('discounted_pct') }}">
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Discount Start Date</label>
                                        <input class="form-control datepicker" type="text" name="discount_start_date" value="{{ old('discount_start_date') }}" readonly="readonly">
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Discount End Date</label>
                                        <input class="form-control datepicker" type="text" name="discount_end_date" value="{{ old('discount_end_date') }}" readonly="readonly">
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Facilities</label>
                                        <select class="form-select select2" name="facilities[]" required multiple>
                                            @foreach ($facilities as $facility)
                                                <option value="{{ $facility->id }}">{{ $facility->facility_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select select2" name="status" required data-selected="{{ old('status', "1") }}">
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-12 mb-4">
                                    <div class="mt-2 d-flex justify-content-end" id="submit-trigger">
                                        <button type="submit" class="btn btn-primary submit">Submit <i class="fas fa-angle-double-right"></i></button>
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
    <script>
        $(".datepicker").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            // allowInput: true,
        });
    </script>
@endsection


