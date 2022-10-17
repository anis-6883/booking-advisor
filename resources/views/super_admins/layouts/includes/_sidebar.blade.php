<nav class="sidebar">
    <div class="sidebar-header">
       <a href="{{ route('super_admin.dashboard') }}" class="sidebar-brand" style="font-size: 20px">
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
          <li class="nav-item {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}">
             <a href="{{ route('super_admin.dashboard') }}" class="nav-link">
               <i class="link-icon" data-feather="box"></i>
               <span class="link-title">Dashboard</span>
             </a>
          </li>
          <li class="nav-item nav-category">Hotel</li>
          
          
          <li class="nav-item {{ request()->routeIs('super_admin.hotels*') ? 'active' : '' }}">
             <a class="nav-link" data-bs-toggle="collapse" href="#hotels" role="button" aria-expanded="{{ request()->routeIs('super_admin.hotels*') ? 'true' : 'false' }}" aria-controls="hotels">
             <i class="link-icon" data-feather="coffee"></i>
             <span class="link-title">Hotel</span>
             <i class="link-arrow" data-feather="chevron-down"></i>
             </a>
             <div class="collapse {{ request()->routeIs('super_admin.hotels*') ? 'show' : '' }}" id="hotels">
                <ul class="nav sub-menu">
                   <li class="nav-item">
                      <a href="{{ route('super_admin.hotels.index') }}" class="nav-link {{ request()->routeIs('super_admin.hotels.index') ? 'active' : '' }}">
                        Hotel List
                     </a>
                   </li>
                   <li class="nav-item">
                      <a href="{{ route('super_admin.hotels.create') }}" class="nav-link {{ request()->routeIs('super_admin.hotels.create') ? 'active' : '' }}">
                        Add Hotel
                     </a>
                   </li>
                </ul>
             </div>
          </li>

          <li class="nav-item nav-category">Admin</li>

          <li class="nav-item {{ request()->routeIs('super_admin.admins*') ? 'active' : '' }}">
             <a class="nav-link" data-bs-toggle="collapse" href="#admins" role="button" aria-expanded="{{ request()->routeIs('super_admin.admins*') ? 'true' : 'false' }}" aria-controls="admins">
             <i class="link-icon" data-feather="user"></i>
             <span class="link-title">Admin</span>
             <i class="link-arrow" data-feather="chevron-down"></i>
             </a>
             <div class="collapse {{ request()->routeIs('super_admin.admins*') ? 'show' : '' }}" id="admins">
                <ul class="nav sub-menu">
                   <li class="nav-item">
                      <a href="{{ route('super_admin.admins.index') }}" class="nav-link {{ request()->routeIs('super_admin.admins.index') ? 'active' : '' }}">
                        Admin List
                     </a>
                   </li>
                   <li class="nav-item">
                      <a href="{{ route('super_admin.admins.create') }}" class="nav-link {{ request()->routeIs('super_admin.admins.create') ? 'active' : '' }}">
                        Add Admin
                     </a>
                   </li>
                </ul>
             </div>
          </li>
          
       </ul>
    </div>
</nav>