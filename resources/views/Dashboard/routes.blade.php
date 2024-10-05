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
                <button id="updateZonesBtn" data-bs-toggle="modal" data-bs-target="#updateZones" class="btn btn-primary btn-round">Update Zones</button>
              </div>

            </div>
                <div class="rounded" id="map"></div>
            </div>

          </div>
        </div>

      </div>

    </div>



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

        <div class="d-flex w-100 justify-content-end">
            <button id="addAssignBaranggayToZone" class="btn btn-primary"><i class="fa fa-plus"></i> Assign Baranggay to zones</button>
        </div>

        <div class="d-none w-100 justify-content-end">
            <button id="closeAssignBaranggayToZone" class="btn btn-label-danger"><i class="fa fa-minus"></i> Close</button>
        </div>

        <div class="form-group">
            <label for="filterByZones">Filter By Zones</label>
            <select  class="form-select" id="filterByZones">

            </select>
        </div>

        <div id="addAssignedBrgyDiv" class="row d-none mt-2">
            <div class="col-6">
                <div class="form-group">
                    <label for="selectZones">List of Zones</label>
                    <select  class="form-select" id="selectZones">

                    </select>
                </div>
            </div>

            <div class="col-6 border rounded p-2">

                <h6>Baranggay List</h6>

                <div id="assignBrgyHolder" style="height: 40vh; overflow-y:auto">
                    <div class="w-100 d-flex justify-content-center align-items-center flex-column">
                        <img width="50" height="50" src="https://img.icons8.com/ios/50/nothing-found.png" alt="nothing-found"/>
                        <h3 class="text-muted">Nothing to show</h3>
                        <small class="text-muted">Select Zone to show all baranggay list</small>
                    </div>
                </div>


            </div>
        </div>

        <div id="brgyListDiv" class="w-100">
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


      </div>
      <div class="modal-footer border-0">
        <button
          type="button"
          id="addBrgyToZone"
          class="btn btn-primary d-none"
          >
          Update Baranggays to the zone
        </button>
        <button
        id="closeUpdateZoneModal";
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
        <span class="fw-mediumbold">Assign</span>
        <span class="fw-light"> Driver </span>
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
      Assign Driver to each zones
      </p>
      

    </div>
    <div class="modal-footer border-0">
      <button
        type="button"
        id="addBrgyToZone"
        class="btn btn-primary d-none"
        >
        Update Baranggays to the zone
      </button>
      <button
      id="closeUpdateZoneModal";
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
