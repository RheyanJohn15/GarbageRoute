<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Map Navigator'])
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
        @include('Components.nav', ['active'=>'mapnavigator'])

      <div class="main-panel">
        @include('Components.headernav')

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Map Navigator</h3>
                <h6 class="op-7 mb-2">Track all active drivers that currently collecting waste</h6>
              </div>
            </div>

            <div id="map" class="mb-4" style="height:70vh; width:100%; border: 1px solid black"></div>
      
          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->
    @vite('resources/js/app.js')
    <script>
      setTimeout(() => {
        window.Echo.channel('gps-update')
          .listen('GpsUpdate', (e)=> {
            updateRouteStatus(e);
        })
      }, 2000);
    </script>
        @include('Components.script', ['type'=> 'admin'])
    <script src="{{asset('Scripts/mapnavigator.js')}}"></script>
  </body>
</html>
