<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Driver Dashboard'])
  </head>
  <style>
    #map {
    width: 100%;
    height: 70vh;
}
  </style>
  <body>
    @include('Components.dashload')

    <div class="wrapper">
        @include('Components.userNav', ['active'=>'dashboard'])

      <div class="main-panel">
        @include('Components.userNavHeader')

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Map & Zone</h3>
                <h6 class="op-7 mb-2">Current Assigned Zone</h6>
              </div>
            </div>
      
            <div class="list-group" id="routeList">
              <div id="map" style="border: 1px solid black"></div>
            </div>

          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->
  
    @include('Components.script', ['type'=> 'driver'])
    <script src="{{asset('Scripts/userDashboard.js')}}"></script>
  </body>
</html>
