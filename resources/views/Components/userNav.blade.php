   <!-- Sidebar -->
   <div class="sidebar" data-background-color="white">
    <div class="sidebar-logo">
      <!-- Logo Header -->
      <div class="logo-header" data-background-color="purple">
        <a href="{{route('dashboard')}}" class="logo">
          <img
            src="{{asset('assets/img/logo.png')}}"
            alt="navbar brand"
            class="navbar-brand"
            height="70"
          />
        </a>
        <div class="nav-toggle">
          <button class="btn btn-toggle toggle-sidebar">
            <i class="gg-menu-right"></i>
          </button>
          <button class="btn btn-toggle sidenav-toggler">
            <i class="gg-menu-left"></i>
          </button>
        </div>
        <button class="topbar-toggler more">
          <i class="gg-more-vertical-alt"></i>
        </button>
      </div>
      <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
      <div class="sidebar-content">
        <ul class="nav nav-secondary">
          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">User Navigation</h4>
          </li>
          <li class="nav-item  {{$active == 'dashboard' ? 'active' : ''}}">
            <a href="{{route('userDashboard')}}">
              <i class="fas fa-map"></i>
              <p>Map & Assigned Zone</p>
            </a>
          </li>

          <li class="nav-item  {{$active == 'history' ? 'active' : ''}}">
            <a href="{{route('userHistory')}}">
              <i class="fas fa-book"></i>
              <p>History</p>
            </a>
          </li>
    
          
        </ul>
      </div>
    </div>
  </div>
  <!-- End Sidebar -->