<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Routes'])
  </head>
  <style>
    #map {
    width: 100%;
    height: 50vh;
}
  </style>
  <body>
    @include('Components.dashload')

    <div class="wrapper">
        @include('Components.nav', ['active'=>'routes'])

      <div class="main-panel">
        @include('Components.headernav')

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Routes & Schedules</h3>
                <h6 class="op-7 mb-2">Manage all dump truck routes and schedule</h6>
              </div>

            </div>
            <div id="map"></div>
            </div>

          </div>
        </div>

      </div>

    </div>




    @include('Components.script', ['type'=> 'admin'])
    <script src="{{asset('Scripts/route.js')}}"></script>
  </body>
</html>
