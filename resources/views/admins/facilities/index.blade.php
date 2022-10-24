@extends('admins.layouts.app')

@section('page_title', 'Room Facility List')

@section('admins_content')
<nav class="page-breadcrumb">
   <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class='fas fa-home text-muted'></i></a></li>
       <li class="breadcrumb-item active" aria-current="page">Room Facility</li>
   </ol>
</nav>

<div class="row">
   <div class="col-12 col-xl-12 stretch-card">
      <div class="card">
         <div class="card-body">
            <h3 class="mb-3">Facility <span style="color: #0C32DC;">List</span></h3>
            <div class="d-flex justify-content-end">
               <a class="btn btn-outline-primary btn-sm" href="{{ route("admin.room-facilities.create") }}">
                  <i class="fas fa-plus mr-1" style="font-size: 13px"></i> Add Facility
               </a>
            </div>
            <hr>

            <div class="table-responsive">
                  <table id="data-table" class="table table-bordered table-striped" style="width:100%">
                     <thead>
                         <tr>
                             <th style=" white-space: nowrap;">Facility Name</th>
                             <th style=" white-space: nowrap; width: 10%;">Status</th>
                             <th class="text-center">Action</th>
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
        ajax: _url + "/admin/room-settings/room-facilities",
        "columns" : [

        { data : "facility_name", name : "facility_name" },
        { data : "status", name : "status", className : "text-center", orderable : false, searchable : false },
        { data : "action", name : "action", orderable : false, searchable : false, className : "text-center" }

        ],
        responsive: true,
        "bStateSave": true,
        "bAutoWidth":false, 
        "ordering": false
    });
</script>
@endsection