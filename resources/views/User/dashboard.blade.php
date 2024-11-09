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

            <div id="notScheduleToday" class="w-100 d-none justify-content-center flex-column gap-2 align-items-center mt-4">
              <h4 class="text-muted">Not your schedule of collection today</h4>
                <img src="/assets/img/nosched.png" class="w-50" alt="Not your schedule today">
            </div>

              <div id="todaySchedule">
                <ul class="nav nav-tabs nav-line nav-color-secondary" id="line-tab" role="tablist">
                  <li class="nav-item submenu" role="presentation">
                    <a class="nav-link" id="line-home-tab" data-bs-toggle="pill" href="#info" role="tab" aria-controls="info" aria-selected="false" tabindex="-1">Zone Assigned Info</a>
                  </li>
                  <li class="nav-item submenu" role="presentation">
                    <a class="nav-link active" id="line-profile-tab" data-bs-toggle="pill" href="#mapTab" role="tab" aria-controls="map" aria-selected="true">Map Collection</a>
                  </li>
                  <li class="nav-item" onclick="loadRecords()" role="presentation">
                    <a class="nav-link" id="line-contact-tab" data-bs-toggle="pill" href="#records" role="tab" aria-controls="records" aria-selected="false" tabindex="-1">Records</a>
                  </li>
                </ul>
                <div class="tab-content mt-3 mb-3" id="line-tabContent">
                  <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info">
                    <h1 id="infoAssignedZone" class="text-center">Loading...</h1>
  
                    <table id="infoTable" class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Context</th>
                          <th scope="col">Value</th>
                        </tr>
                      </thead>
                      <tbody id="infoTableBody">
                        <tr>
                          <td colspan="2" class="text-center">Loading......</td>
                        </tr>
                      </tbody>
                    </table>
  
                    </div>
                  <div class="tab-pane fade active show" id="mapTab" role="tabpanel" aria-labelledby="map">
                    <button id="cpmpleteCollectionBtn" class="btn btn-success w-100 mb-3" disabled>Go to your collection waypoint</span></button>
                    <button id="turnOverToDumpsite" class="btn btn-success w-100 mb-3 d-none" disabled> Dumpsite Turn Over </span></button>
                      <div id="currentLocationDiv" class="d-none">
                          <h5>Current Location</h5>
                          <ul>
                              <li>Location: <span id="currentLocationName">Loading...</span></li>
                          </ul>
                      </div>
                    <div id="map" style="border: 1px solid black"></div>
                  </div>
                  <div class="tab-pane fade" id="records" role="tabpanel" aria-labelledby="records">
                    <h3>Collection Reports</h3>
                    <table id="collectionReports" class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                      <thead>
                        <tr>
                          <th scope="col">Waypoint Location</th>
                          <th scope="col">Time Completed </th>
                          <th scope="col">Date</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                      </tbody>
                    </table>
  
                    <h3>Dumpsite Turn Over Reports</h3>
                    <table id="dumpsiteTurnOverRecords" class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                      <thead>
                        <tr>
                          <th scope="col">Month-Year</th>
                          <th scope="col">Total Turn Overs(Tons)</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                      </tbody>
                    </table>
  
                  </div>
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
