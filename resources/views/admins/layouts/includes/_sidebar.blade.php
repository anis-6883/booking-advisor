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
          <li class="nav-item nav-category">Main</li>
          <li class="nav-item">
             <a href="{{ route('admin.dashboard') }}" class="nav-link">
               <i class="link-icon" data-feather="box"></i>
               <span class="link-title">Dashboard</span>
             </a>
          </li>
          <li class="nav-item nav-category">Staff</li>
          <li class="nav-item">
             <a class="nav-link" data-bs-toggle="collapse" href="#emails" role="button" aria-expanded="false" aria-controls="emails">
             <i class="link-icon" data-feather="coffee"></i>
             <span class="link-title">Staff</span>
             <i class="link-arrow" data-feather="chevron-down"></i>
             </a>
             <div class="collapse" id="emails">
                <ul class="nav sub-menu">
                   <li class="nav-item">
                      <a href="pages/email/inbox.html" class="nav-link">Staff List</a>
                   </li>
                   <li class="nav-item">
                      <a href="pages/email/read.html" class="nav-link">Read</a>
                   </li>
                   <li class="nav-item">
                      <a href="pages/email/compose.html" class="nav-link">Compose</a>
                   </li>
                </ul>
             </div>
          </li>
       </ul>
    </div>
</nav>