@extends('admins.layouts.app')

@section('page_title', 'Add Bed Type')

@section('admins_content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class='fas fa-home text-muted'></i></a></li>
        <li class="breadcrumb-item"> <a class="text-muted" href="{{ route('admin.bed-types.index') }}">Bed Type</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create</li>
    </ol>
</nav>
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Add New <span style="color: #0C32DC;">Bed Type</span></h3>
                        <hr>
                        <form action="{{ route('admin.bed-types.store') }}" method="POST" autocomplete="off">
                            @csrf
                            @method('POST')

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Bed Type Name</label>
                                        <input type="text" class="form-control" name="bed_type_name" value="{{ old('bed_type_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select select2" name="status" required data-selected="{{ old('status', "1") }}">
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                </div><!-- Col -->
                                <div class="col-sm-12 mt-2">
                                    <div class="mt-2 d-flex justify-content-start">
                                        <button type="submit" class="btn btn-primary submit">Submit <i class="fas fa-angle-double-right"></i></button>
                                    </div>
                                </div><!-- Col -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- row -->
@endsection