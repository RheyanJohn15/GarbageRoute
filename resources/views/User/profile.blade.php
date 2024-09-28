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
      
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->

    @include('Components.script', ['type'=> 'driver'])
  </body>
</html>
