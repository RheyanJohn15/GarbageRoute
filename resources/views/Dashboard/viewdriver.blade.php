<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Truck Driver Details'])
  </head>
  <body>
    @include('Components.dashload')
    <div class="wrapper">
        @include('Components.nav', ['active'=>'driver'])

      <div class="main-panel">
        @include('Components.headernav')

        <div class="container">
          <div class="page-inner">
            <div
            class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
          >
            <div>
              <h3 class="fw-bold mb-3">Driver Details</h3>
            </div>
    
          </div>


          <div class="row">
            <div class="col-3">
              <div class="avatar avatar-xxl">
                <img src="../assets/img/loader.gif" id="driverImage" alt="DriverImage" class="avatar-img rounded-circle">
              </div>
            </div>

            <div class="col-9">
              <p class="fw-bold fs-4">Name: <span id="name" class="fw-normal">Loading...</span></p>
              <p class="fw-bold fs-4">Username: <span id="username" class="fw-normal">Loading...</span></p>
              <p class="fw-bold fs-4">License: <span id="license" class="fw-normal">Loading...</span></p>
              <p class="fw-bold fs-4">Contact: <span id="contact" class="fw-normal">Loading...</span></p>
              <p class="fw-bold fs-4">Address: <span id="address" class="fw-normal">Loading...</span></p>
            </div>
          </div>
        
          </div>
        </div>

      </div>

    </div>

    <script src="/Scripts/viewDriver.js"></script>
    @include('Components.script', ['type'=> 'admin'])
  </body>
</html>
