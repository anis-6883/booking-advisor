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

          <li class="nav-item nav-category">Role & Permission</li>

          <li class="nav-item {{ request()->routeIs('admin.staffs*') ? 'active' : '' }}">
             <a class="nav-link" data-bs-toggle="collapse" href="#roles" role="button" aria-expanded="{{ request()->routeIs('admin.staffs*') ? 'true' : 'false' }}" aria-controls="roles">
             <i class="link-icon" data-feather="shield"></i>
             <span class="link-title">Role & Permission</span>
             <i class="link-arrow" data-feather="chevron-down"></i>
             </a>
             <div class="collapse {{ request()->routeIs('admin.staffs*') ? 'show' : '' }}" id="roles">
                <ul class="nav sub-menu">
                   <li class="nav-item">
                      <a href="{{ route('admin.staffs.index') }}" class="nav-link {{ request()->routeIs('admin.staffs.index') ? 'active' : '' }}">Role List</a>
                   </li>
                   <li class="nav-item">
                      <a href="{{ route('admin.staffs.create') }}" class="nav-link {{ request()->routeIs('admin.staffs.create') ? 'active' : '' }}">Add Role</a>
                   </li>
                </ul>
             </div>
          </li>
       </ul>
    </div>
</nav>