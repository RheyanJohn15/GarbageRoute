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
          <li class="nav-item {{$active == 'dashboard' ? 'active' : ''}}">
            <a href="{{route('dashboard')}}">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">Admin Navigation</h4>
          </li>
          <li class="nav-item  {{$active == 'routes' ? 'active' : ''}}">
            <a href="{{route('routes')}}">
              <i class="fas fa-route"></i>
              <p>Routes & Schedule</p>
            </a>
          </li>
          <li class="nav-item  {{$active == 'truck' ? 'active' : ''}}">
            <a data-bs-toggle="collapse" href="#sidebarLayouts">
              <i class="fas fa-truck-moving "></i>
              <p>Truck & Driver</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="sidebarLayouts">
              <ul class="nav nav-collapse">
                <li>
                  <a href="{{route('truckregister')}}">
                    <span class="sub-item">Trucks</span>
                  </a>
                </li>
                <li>
                  <a href="{{route('truckdriver')}}">
                    <span class="sub-item">Drivers</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="nav-item  {{$active == 'complaints' ? 'active' : ''}}">
            <a href="{{route('complaints')}}">
              <i class="fas fa-paper-plane"></i>
              <p>Complaints</p>
            </a>
          </li>
          <li class="nav-item  {{$active == 'mapnavigator' ? 'active' : ''}}">
            <a href="{{route('mapnavigator')}}">
              <i class="fas fa-map-marked-alt"></i>
              <p>Map Navigator</p>
            </a>
          </li>
         
    
          
        </ul>
      </div>
    </div>
  </div>
  <!-- End Sidebar -->