<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Drivers Profile'])
  </head>
  <body>
    @include('Components.dashload')

    <div class="wrapper">
        @include('Components.userNav', ['active'=>' '])

      <div class="main-panel">
        @include('Components.userNavHeader')

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Driver Profile</h3>
                <h6 class="op-7 mb-2">Drivers Account Profile Information</h6>
              </div>
            </div>
      
            <div class="d-flex w-100 gap-4 justify-content-center align-items-center flex-column">
              <div class="avatar avatar-xxl">
                <img src="/assets/img/load.jfif" alt="..." class="avatar-img rounded-circle">
              </div>

              <div class="row w-50">
                  <div class="col-6">
                    <h2>Name</h2>
                    <p>License</p>
                    <p>Address</p>
                    <p>Contact</p>
                    <p>Username</p>
                  </div>
                  <div class="col-6">
                      <h2>Assigned Truck</h2>
                      <p>Model</p>
                      <p>Capacity</p>
                  </div>
              </div>
            </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->

    @include('Components.script', ['type'=> 'driver'])
    <script src="/Scripts/userProfile.js"></script>
  </body>
</html>
