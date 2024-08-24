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
    @vite('resources/js/app.js')
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

              <div class="ms-md-auto py-2 py-md-0">
                <button class="btn btn-label-info btn-round me-2">Close View</button>
                <button id="addRouteBtn" class="btn btn-primary btn-round">Add Routes</button>
              </div>
            </div>
            <table
            class="table table-hover table-bordered table-head-bg-info table-bordered-bd-info mt-4"
          >
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Handle</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Jacob</td>
                <td>Thornton</td>
                <td>@fat</td>
              </tr>
              <tr>
                <td>3</td>
                <td colspan="2">Larry the Bird</td>
                <td>@twitter</td>
              </tr>
            </tbody>
          </table>
            <div class="card" id="addRouteCard">
              <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">Add Route</div>
                <div class="d-flex gap-2">
                  <button class="btn btn-label-danger gap-2 align-items-center" id="clearWaypoints"  style="display: none"> <i class="fas fa-minus-circle"></i>Remove Route</button>
                  <button class="btn btn-primary d-flex gap-2 align-items-center"> <i class="fas fa-save"></i> Save</button>
                </div>
              </div>
              <div class="card-body">
                <div class="form-floating form-floating-custom mb-3">
                  <input
                    type="text"
                    class="form-control"
                    id="floatingInput"
                    placeholder="Route 1"
                  />
                  <label for="floatingInput">Route Name</label>
                </div>
                <div id="map" class="mb-4" style="height:50vh; width:100%; border: 1px solid black"></div>

                <p>Start Route: <span id="startRouteSpan"></span></p>
                <p>End Route: <span id="endRouteSpan"></span></p>

                <label for="selectDriver" class="form-label">Assigned Truck Driver</label>
                <select id="selectDriver">
                  
                </select>
              </div>
            </div>
          
          </div>
        </div>

      </div>

    </div>

    <form id="saveRouteForm">
      @csrf
      <input type="hidden" name="name" id="saveRouteName">
      <input type="hidden" name="start_longitude" id="saveRouteStartLongitude">
      <input type="hidden" name="start_latitude" id="saveRouteStartLatitude">
      <input type="hidden" name="end_longitude" id="saveRouteEndLongitude">
      <input type="hidden" name="end_latitude" id="saveRouteEndLatitude">
      <input type="hidden" name="assigned_truck" id="saveRouteAssignedTruck">
    </form>



    {{-- Add Route Modal --}}
    {{-- <div
    class="modal fade"
    id="addRouteModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title">
            <span class="fw-mediumbold"> New </span>
            <span class="fw-light"> Route </span>
          </h5>
          <button
            type="button"
            class="close"
            data-bs-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="small">
            Create a new route
          </p>
          <form id="addRouteForm" method="post">
            @csrf
            <div class="row">
              <div class="col-sm-12">
                <div id="addName_g" class="form-group form-group-default">
                  <label>Route Name</label>
                  <input
                    id="addName"
                    type="text"
                    name="name"
                    class="form-control"
                    placeholder="Full Name"
                  />
                  <small id="addName_e" style="display: none" class="text-danger">(This field is required)</small>
                </div>
              </div>
             
            
            </div>
          </form>
        </div>
        <div class="modal-footer border-0">
          <button
            type="button"
            id="addDriver"
            class="btn btn-primary"
            >
            Add
          </button>
          <button
          id="closeButton";
            type="button"
            class="btn btn-danger"
            data-bs-dismiss="modal"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div> --}}

    <!--   Core JS Files   -->
   {{-- <script>
    setTimeout(() => {
      window.Echo.channel('gps-update')
      .listen('GpsUpdate', (e)=> {
        console.log(e);
      })
    }, 2000);
   </script> --}}

    @include('Components.script')
    <script src="{{asset('Scripts/route.js')}}"></script>
  </body>
</html>
