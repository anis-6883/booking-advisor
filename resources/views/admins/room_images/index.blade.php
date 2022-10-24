@extends('admins.layouts.app')

@section('page_title', 'Room Image List')

@section('admins_content')
<nav class="page-breadcrumb">
   <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class='fas fa-home text-muted'></i></a></li>
       <li class="breadcrumb-item active" aria-current="page">Room Image</li>
   </ol>
</nav>

<div class="row">
   <div class="col-12 col-xl-12 stretch-card">
      <div class="card">
         <div class="card-body">
            <h3 class="mb-3">Room Image <span style="color: #0C32DC;">List</span></h3>
            <hr>

            <div class="table-responsive">
                  <table id="data-table" class="table table-bordered table-striped" style="width:100%">
                     <thead>
                         <tr>
                             <th style=" white-space: nowrap; width: 20%;">Room Title</th>
                             <th style=" white-space: nowrap;">Images</th>
                             <th style=" white-space: nowrap; width: 30%;">Choose Images</th>
                             <th style="width: 10%;" class="text-center">Action</th>
                         </tr>
                     </thead>
                 </table>
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

   $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: _url + "/admin/room-settings/room-images",
        "columns" : [

        { data : "room_title", name : "room_title" },
        { data : "images", name : "images" },
        { data : "add_images", name : "add_images" },
        { data : "action", name : "action", orderable : false, searchable : false, className : "text-center" }

        ],
        responsive: true,
        "bStateSave": true,
        "bAutoWidth":false, 
        "ordering": false
    });
</script>
@endsection