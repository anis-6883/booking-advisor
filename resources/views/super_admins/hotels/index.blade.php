@extends('super_admins.layouts.app')

@section('page_title', 'Hotel List')

@section('super_admins_content')
<nav class="page-breadcrumb">
   <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('super_admins.dashboard') }}"><i class='fas fa-home text-muted'></i></a></li>
       <li class="breadcrumb-item active" aria-current="page">Hotels</li>
   </ol>
</nav>

<div class="row">
   <div class="col-12 col-xl-12 stretch-card">
      <div class="card">
         <div class="card-body">
            <h3 class="mb-3">Hotel <span style="color: #0C32DC;">List</span></h3>
            <div class="d-flex justify-content-end">
               <a class="btn btn-outline-primary btn-sm" href="{{ route("super_admins.hotels.create") }}">
                  <i class="fas fa-plus mr-1" style="font-size: 13px"></i> Add Hotel
               </a>
            </div>
            <hr>

            <div class="table-responsive">
                  <table id="data-table" class="table table-bordered table-striped" style="width:100%">
                     <thead>
                         <tr>
                             <th style=" white-space: nowrap;">Name</th>
                             <th style=" white-space: nowrap;">Email</th>
                             <th style=" white-space: nowrap;">Division</th>
                             <th style=" white-space: nowrap;">District</th>
                             <th style=" white-space: nowrap;">Upazila</th>
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
        ajax: _url + "/super-admin/hotels",
        "columns" : [

        { data : "hotel_name", name : "hotel_name" },
        { data : "hotel_email", name : "hotel_email" },
        { data : "division", name : "division" },
        { data : "district", name : "district" },
        { data : "upazila", name : "upazila" },
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