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
              <h3 class="fw-bold mb-3">Drivers</h3>
              <h6 class="op-7 mb-2">Manage All Trucks Drivers in the City</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
              <button style="display: none" id="closeView" onclick="CloseView()" class="btn btn-label-danger btn-round me-2">Close View</button>
              <button data-bs-toggle="modal" data-bs-target="#addDriverModal" class="btn btn-primary btn-round">Add Driver</button>
            </div>
          </div>

        
          </div>
        </div>

      </div>

    </div>

   
    @include('Components.script', ['type'=> 'admin'])
  </body>
</html>
