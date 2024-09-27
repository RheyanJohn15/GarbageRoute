<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Map Navigator'])
  </head>
  <body>
    @vite('resources/js/app.js')
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
                <h6 class="op-7 mb-2">Blank Page</h6>
              </div>
            </div>
      
          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->
    <script>
      setTimeout(() => {
        window.Echo.channel('gps-update')
          .listen('GpsUpdate', (e)=> {
          console.log(e);
        })
      }, 2000);
    </script>
    @include('Components.script')
    <script class="{{asset('Scripts/mapnavigator.js')}}"></script>
  </body>
</html>
