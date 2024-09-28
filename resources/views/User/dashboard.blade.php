<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Driver Dashboard'])
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
                <h3 class="fw-bold mb-3">Routes & Schedule List</h3>
                <h6 class="op-7 mb-2">List of all routes assigned to you</h6>
              </div>
            </div>
      
            <div class="list-group" id="routeList">
              <div class="w-100 d-flex justify-content-center align-items-center flex-column gap-2" style="height: 50vh">
                <div class="contentLoader"></div>
                <p>Loading Routes please wait.....</p>
              </div>
            
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
