<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Driver Route Journey'])
   <link rel="stylesheet" href="{{asset('assets/user/map.css')}}">
  </head>
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
                <h3 class="fw-bold mb-3">Start your route Journey</h3>
                <h6 class="op-7 mb-2">Way points are the collection point of garbage gathering</h6>
              </div>
            </div>
            <button class="btn btn-secondary w-100 mb-2" id="startGarbageCollection">
                <span class="btn-label">
                  <i class="fa fa-map"></i>
                </span>
                Start Garbage Collection
              </button>
            <div id="map" class="w-100" style="height: 70vh"></div>
          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->

    @include('Components.script')
    <script src="{{asset('Scripts/journey.js')}}"></script>
  </body>
</html>
