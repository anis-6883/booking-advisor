<nav class="sidebar">
    <div class="sidebar-header">
       <a href="{{ route('admin.dashboard') }}" class="sidebar-brand" style="font-size: 20px">
       Booking<span>Advisor</span>
       </a>
       <div class="sidebar-toggler not-active">
          <span></span>
          <span></span>
          <span></span>
       </div>
    </div>
    <div class="sidebar-body">
       <ul class="nav">
          <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
             <a href="{{ route('admin.dashboard') }}" class="nav-link">
               <i class="link-icon" data-feather="box"></i>
               <span class="link-title">Dashboard</span>
             </a>
          </li>

          @if (App\Models\Admin::isAdmin(Auth::guard('admin')->user()->role_id))

            <li class="nav-item nav-category">Staff</li>

            <li class="nav-item {{ request()->routeIs('admin.staffs*') ? 'active' : '' }}">
               <a class="nav-link" data-bs-toggle="collapse" href="#staffs" role="button" aria-expanded="{{ request()->routeIs('admin.staffs*') ? 'true' : 'false' }}" aria-controls="staffs">
               <i class="link-icon" data-feather="users"></i>
               <span class="link-title">Staff</span>
               <i class="link-arrow" data-feather="chevron-down"></i>
               </a>
               <div class="collapse {{ request()->routeIs('admin.staffs*') ? 'show' : '' }}" id="staffs">
                  <ul class="nav sub-menu">
                     <li class="nav-item">
                        <a href="{{ route('admin.staffs.index') }}" class="nav-link {{ request()->routeIs('admin.staffs.index') ? 'active' : '' }}">Staff List</a>
                     </li>
                     <li class="nav-item">
                        <a href="{{ route('admin.staffs.create') }}" class="nav-link {{ request()->routeIs('admin.staffs.create') ? 'active' : '' }}">Add Staff</a>
                     </li>
                  </ul>
               </div>
            </li>

          @endif

          <li class="nav-item nav-category">Room Settings</li>

         <li class="nav-item {{ request()->is('admin/room-settings/*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#room_settings" role="button" aria-expanded="{{ request()->is('admin/room-settings/*') ? 'true' : 'false' }}" aria-controls="room_settings">
            <i class="link-icon" data-feather="settings"></i>
            <span class="link-title">Room Settings</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ request()->is('admin/room-settings/*') ? 'show' : '' }}" id="room_settings">
               <ul class="nav sub-menu">
                  <li class="nav-item">
                     <a href="{{ route('admin.bed-types.index') }}" class="nav-link {{ request()->routeIs('admin.bed-types.*') ? 'active' : '' }}">Bed Type</a>
                  </li>
                  <li class="nav-item">
                     <a href="{{ route('admin.room-facilities.index') }}" class="nav-link {{ request()->routeIs('admin.room-facilities.*') ? 'active' : '' }}">Room Facility</a>
                  </li>
                  <li class="nav-item">
                     <a href="{{ route('admin.rooms.index') }}" class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">Room</a>
                  </li>
                  <li class="nav-item">
                     <a href="{{ route('admin.room-images.index') }}" class="nav-link {{ request()->routeIs('admin.room-images.*') ? 'active' : '' }}">Room Images</a>
                  </li>
               </ul>
            </div>
         </li>

       </ul>
    </div>
</nav>