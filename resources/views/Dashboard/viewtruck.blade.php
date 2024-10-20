<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Truck Details'])
  </head>
  <body>
    @include('Components.dashload')
    <div class="wrapper">
        @include('Components.nav', ['active'=>'truck'])

      <div class="main-panel">
        @include('Components.headernav')

        <div class="container">
          <div class="page-inner">
            <div
            class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
          >
            <div>
              <h3 class="fw-bold mb-3">Truck Details</h3>
            </div>

          </div>

          <div class="row">
            <div class="col-3">
              <div class="avatar avatar-xxl">
                <img src="../assets/img/loader.gif" alt="TruckImage" id="truckImage" class="avatar-img rounded-circle">
              </div>
            </div>
            <div class="col-9">
              <p class="fw-bold fs-4">Model: <span id="model" class="fw-normal">Loading...</span></p>
              <p class="fw-bold fs-4">Capacity: <span id="capacity" class="fw-normal">Loading...</span></p>
              <p class="fw-bold fs-4">Plate Number: <span id="plate_num" class="fw-normal">Loading...</span></p>
              <p class="fw-bold fs-4">Driver: <span id="driver" class="fw-normal">Loading...</span></p>
            </div>
          </div>
        
          </div>
        </div>

      </div>

    </div>

    <script src="/Scripts/viewTruck.js"></script>
    @include('Components.script', ['type'=> 'admin'])
  </body>
</html>
