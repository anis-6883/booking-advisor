@extends('admins.layouts.app')

@section('page_title', 'Edit Room Image')

@section('admins_content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class='fas fa-home text-muted'></i></a></li>
        <li class="breadcrumb-item"> <a class="text-muted" href="{{ route('admin.bed-types.index') }}">Room Image</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav>
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Edit <span style="color: #0C32DC;">Room Image</span></h3>
                        <hr>
                        <div class="d-flex flex-wrap">
                            @foreach ($room_images as $image)
                            <div class="card" style="width: 10rem; margin: 0px 10px">
                                <img src="{{ asset($image->image_file) }}" class="card-img-top" alt="room_image" width="100%">
                                <div class="card-body text-center">
                                    @if ($image->status)
                                        <span id="status{{ $image->id }}" onclick="changeStatus({{ $image->id }})" style="cursor: pointer" class="badge rounded-pill border border-success text-success mb-2">Active</span>
                                    @else
                                        <span id="status{{ $image->id }}" onclick="changeStatus({{ $image->id }})" style="cursor: pointer" class="badge rounded-pill border border-danger text-danger mb-2">Inactive</span>
                                    @endif
                                    
                                    <button onclick="document.querySelector('#delete_image{{ $image->id }}').submit();" class="btn btn-xs btn-danger">Delete</button>
                                </div>
                            </div>
                            <form method="POST" id="delete_image{{ $image->id }}" action="{{ route('admin.room-images.destroy', $image->id) }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach
                        </div>
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
    var _url = "{{ url('/') }}";

    function changeStatus(image_id) {
        $(function() {
            var statusBtn = $(`#status${image_id}`);
            var statusText = statusBtn.text();
            $.ajax({
                url: _url + "/update-status",
                type: "POST",
                data: {
                    id: image_id,
                    status: statusText === "Active" ? 0 : 1,
                    table: "room_images",
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.result) {
                        if (statusText === "Active") {
                            statusBtn.text("Inactive");
                            statusBtn.removeClass("border-success");
                            statusBtn.removeClass("text-success");
                            statusBtn.addClass("border-danger");
                            statusBtn.addClass("text-danger");
                        } else {
                            statusBtn.text("Active");
                            statusBtn.removeClass("border-danger");
                            statusBtn.removeClass("text-danger");
                            statusBtn.addClass("border-success");
                            statusBtn.addClass("text-success");
                        }
                    }
                }
            });
        });
    }
</script>
@endsection