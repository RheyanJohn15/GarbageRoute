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

              <div class="ms-md-auto py-2 py-md-0">
                <button id="closeView" onclick="CloseView()" class="btn btn-label-info btn-round me-2">Assign Drivers</button>
                <button data-bs-toggle="modal" data-bs-target="#updateZones" class="btn btn-primary btn-round">Update Zones</button>
              </div>

            </div>
                <div class="rounded" id="map"></div>
            </div>

          </div>
        </div>

      </div>

    </div>


  {{-- Update Modal Driver --}}
  <div
  class="modal fade"
  id="updateZones"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">
          <span class="fw-mediumbold">Update</span>
          <span class="fw-light"> Zones </span>
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
         Silay City Barangays
        </p>
        <div class="form-group">
            <label for="filterByZones">Filter By Zones</label>
            <select name="" class="form-select" id="filterByZones">
                <option value="all">All Baranggays</option>
            </select>
        </div>
        <table id="silayBrgy"
        class="table table-hover table-bordered table-head-bg-info table-bordered-bd-info mt-4"
      >
        <thead>
          <tr>
            <th>Baranggay</th>
            <th>Assigned Zone</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="6"><div style="height: 5vh" class="d-flex justify-content-center align-items-center">Loading...<img src="{{asset('assets/img/loader.gif')}}" class="h-100"></div></td>
          </tr>
        </tbody>
      </table>


      </div>
      <div class="modal-footer border-0">
        <button
          type="button"
          id="updateDriver"
          class="btn btn-primary"
          >
          Update Driver
        </button>
        <button
        id="closeButtonUpdate";
          type="button"
          class="btn btn-danger"
          data-bs-dismiss="modal"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</div>

    @include('Components.script', ['type'=> 'admin'])
    <script type="module" src="{{asset('Scripts/route.js')}}"></script>
  </body>
</html>
