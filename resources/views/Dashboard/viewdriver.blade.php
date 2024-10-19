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

        
          </div>
        </div>

      </div>

    </div>

    <script src="/Scripts/viewDriver.js"></script>
    @include('Components.script', ['type'=> 'admin'])
  </body>
</html>
