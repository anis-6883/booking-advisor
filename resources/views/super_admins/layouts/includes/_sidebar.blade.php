<nav class="sidebar">
    <div class="sidebar-header">
       <a href="{{ route('super_admins.dashboard') }}" class="sidebar-brand" style="font-size: 20px">
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
          <li class="nav-item nav-category">Main</li>
          <li class="nav-item {{ request()->routeIs('super_admins.dashboard') ? 'active' : '' }}">
             <a href="{{ route('super_admins.dashboard') }}" class="nav-link">
               <i class="link-icon" data-feather="box"></i>
               <span class="link-title">Dashboard</span>
             </a>
          </li>
          <li class="nav-item nav-category">Hotel</li>
          
          
          <li class="nav-item {{ request()->routeIs('super_admins.hotels*') ? 'active' : '' }}">
             <a class="nav-link" data-bs-toggle="collapse" href="#emails" role="button" aria-expanded="{{ request()->routeIs('super_admins.hotels*') ? 'true' : 'false' }}" aria-controls="emails">
             <i class="link-icon" data-feather="coffee"></i>
             <span class="link-title">Hotel</span>
             <i class="link-arrow" data-feather="chevron-down"></i>
             </a>
             <div class="collapse {{ request()->routeIs('super_admins.hotels*') ? 'show' : '' }}" id="emails">
                <ul class="nav sub-menu">
                   <li class="nav-item">
                      <a href="{{ route('super_admins.hotels.index') }}" class="nav-link {{ request()->routeIs('super_admins.hotels.index') ? 'active' : '' }}">
                        Hotel List
                     </a>
                   </li>
                   <li class="nav-item">
                      <a href="{{ route('super_admins.hotels.create') }}" class="nav-link {{ request()->routeIs('super_admins.hotels.create') ? 'active' : '' }}">
                        Add Hotel
                     </a>
                   </li>
                </ul>
             </div>
          </li>
          
       </ul>
    </div>
</nav>