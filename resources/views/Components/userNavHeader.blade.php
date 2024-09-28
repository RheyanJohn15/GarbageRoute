
<div class="main-header">
    <div class="main-header-logo">
      <!-- Logo Header -->
      <div class="logo-header" data-background-color="purple">
        <a href="index.html" class="logo">
          <img
            src="assets/img/kaiadmin/logo_light.svg"
            alt="navbar brand"
            class="navbar-brand"
            height="20"
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
    <!-- Navbar Header -->
    <nav
      class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
      data-background-color="purple"
    >
      <div class="container-fluid">
        <nav
          class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
        >
          <div class="input-group">
            <div class="input-group-prepend">
              <button type="submit" class="btn btn-search pe-1">
                <i class="fa fa-search search-icon"></i>
              </button>
            </div>
            <input
              type="text"
              placeholder="Search ..."
              class="form-control"
            />
          </div>
        </nav>

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
          <li
            class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
          >
            <a
              class="nav-link dropdown-toggle"
              data-bs-toggle="dropdown"
              href="#"
              role="button"
              aria-expanded="false"
              aria-haspopup="true"
            >
              <i class="fa fa-search"></i>
            </a>
            <ul class="dropdown-menu dropdown-search animated fadeIn">
              <form class="navbar-left navbar-form nav-search">
                <div class="input-group">
                  <input
                    type="text"
                    placeholder="Search ..."
                    class="form-control"
                  />
                </div>
              </form>
            </ul>
          </li>


          <li class="nav-item topbar-icon dropdown hidden-caret">
            <a
              class="nav-link"
              data-bs-toggle="dropdown"
              href="#"
              aria-expanded="false"
            >
              <i class="fas fa-layer-group"></i>
            </a>
            <div class="dropdown-menu quick-actions animated fadeIn">
              <div class="quick-actions-header">
                <span class="title mb-1">Quick Actions</span>
                <span class="subtitle op-7">Shortcuts</span>
              </div>
              <div class="quick-actions-scroll scrollbar-outer">
                <div class="quick-actions-items">
                  <div class="row m-0">
                    <a class="col-6 col-md-4 p-0" href="/user/driver/dashboard">
                      <div class="quick-actions-item">
                        <div class="avatar-item bg-danger rounded-circle">
                          <i class="fas fa-route"></i>
                        </div>
                        <span class="text">Routes & Schedule</span>
                      </div>
                    </a>
                    <a class="col-6 col-md-4 p-0" href="/user/driver/history">
                      <div class="quick-actions-item">
                        <div
                          class="avatar-item bg-warning rounded-circle"
                        >
                          <i class="fa fa-book"></i>
                        </div>
                        <span class="text">History</span>
                      </div>
                    </a>

                  </div>
                </div>
              </div>
            </div>
          </li>

          <li class="nav-item topbar-user dropdown hidden-caret">
            <a
              class="dropdown-toggle profile-pic"
              data-bs-toggle="dropdown"
              href="#"
              aria-expanded="false"
            >
              <div class="avatar-sm">
                <img
                  id="userProfilePic"
                  src="/assets/img/load.jfif"
                  alt="..."
                  class="avatar-img rounded-circle"
                />
              </div>
              <span class="profile-username">
                <span class="op-7">Hi,</span>
                <span id="driverHeaderName" class="fw-bold">Loading.....</span>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-user animated fadeIn">
              <div class="dropdown-user-scroll scrollbar-outer">
                <li>
                  <div class="user-box">
                    <div class="avatar-lg">
                      <img
                        id="userProfilePicMobile"
                         src="/assets/img/load.jfif"
                        alt="image profile"
                        class="avatar-img rounded"
                      />
                    </div>
                    <div class="u-text">
                      <h4 id="driverNameSub">Loading......</h4>
                      <p id="driverLicense" class="text-muted">Loading.....</p>
                      <a
                        href="/user/driver/profile"
                        class="btn btn-xs btn-secondary btn-sm"
                        >View Profile</a
                      >
                    </div>
                  </div>
                </li>
                <li>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="/user/driver/profile">My Profile</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="/user/driver/settings">Account Setting</a>
                  <div class="dropdown-divider"></div>
                  <button class="dropdown-item" id="driverlogout">Logout</button>
                </li>
              </div>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End Navbar -->
  </div>
