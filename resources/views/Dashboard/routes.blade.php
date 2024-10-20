<!DOCTYPE html>
<html lang="en">

<head>
    @include('Components.header', ['title' => 'Routes'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        @include('Components.nav', ['active' => 'routes'])

        <div class="main-panel">
            @include('Components.headernav')

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Routes & Schedules</h3>
                            <h6 class="op-7 mb-2">Manage all dump truck routes and schedule</h6>
                        </div>

                        <div class="ms-md-auto py-2 py-md-0" id="headerMenu">
                            <button id="changeDumpsiteLocation" class="btn btn-label-warning btn-round me-2"><i
                                    class="fas fa-warehouse"></i> Change Dumpsite Location </button>
                            <button id="assignDriverBtn"data-bs-toggle="modal" data-bs-target="#assignDriver"
                                class="btn btn-label-info btn-round me-2"><i class="fas fa-user-friends"></i> Assign
                                Drivers</button>
                            <button id="addWaypointBtn" class="btn btn-label-success btn-round me-2"
                                data-bs-toggle="modal" data-bs-target="#addWayPoints"><i
                                    class="fas fa-map-marker-alt"></i> Zone Waypoints </button>
                            <button id="updateZonesBtn" data-bs-toggle="modal" data-bs-target="#updateZones"
                                class="btn btn-label-primary btn-round"> <i class="fas fa-map-marked-alt"></i> Update
                                Zones</button>
                        </div>

                        <div class="ms-md-auto py-2 py-md-0 d-none" id="closeEditWaypointPage">
                            <button class="btn btn-danger btn-round"> <i class="fas fa-times"></i> Close Edit</button>
                        </div>

                    </div>

                    <div id="addZoneEditDetailsDiv" class="w-100 pl-4 d-none row">
                        <div class="col-6">
                            <h4 id="addWaypointDetailHeader">Loading....</h4>
                            <ul id="addWaypointBrgyList">
                            </ul>
                        </div>
                        <div class="col-6 d-flex justify-content-end align-items-end p-4 gap-2">
                            <button class="btn btn-success" id="saveEditWaypoints">
                                <span class="btn-label">
                                    <i class="fa fa-save"></i>
                                </span>
                                Save
                            </button>
                            <button id="redoEditWaypoints" class="btn btn-danger">
                                <span class="btn-label">
                                    <i class="fas fa-redo"></i>
                                </span>
                                Redo
                            </button>
                        </div>
                    </div>
                    <div class="rounded mb-4" id="map"></div>
                    <div id="activeTableDiv">
                        
                    <h3>Currently Collecting Active Drivers</h3>
                    <p>Track All Active drivers and there location on the map</p>
                    <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                        <thead>
                          <tr>
                            <th scope="col">Driver Name</th>
                            <th scope="col">Truck Model</th>
                            <th scope="col">Capacity</th>
                            <th scope="col">Zone</th>
                            <th scope="col">Location</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody id="activeDriverTable">
                            <tr><td colspan="6" class="text-center">Loading......</td></tr>
                        </tbody>
                      </table>
                    </div>

                </div>

            </div>
        </div>

    </div>

    </div>



    <div class="modal fade" id="updateZones" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Update</span>
                        <span class="fw-light"> Zones </span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small">
                        Silay City Barangays
                    </p>

                    <div class="d-flex w-100 justify-content-end">
                        <button id="addAssignBaranggayToZone" class="btn btn-primary"><i class="fa fa-plus"></i> Assign
                            Baranggay to zones</button>
                    </div>

                    <div class="d-none w-100 justify-content-end">
                        <button id="closeAssignBaranggayToZone" class="btn btn-label-danger"><i class="fa fa-minus"></i>
                            Close</button>
                    </div>

                    <div class="form-group">
                        <label for="filterByZones">Filter By Zones</label>
                        <select class="form-select" id="filterByZones">

                        </select>
                    </div>

                    <div id="addAssignedBrgyDiv" class="row d-none mt-2">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="selectZones">List of Zones</label>
                                <select class="form-select" id="selectZones">

                                </select>
                            </div>
                        </div>

                        <div class="col-6 border rounded p-2">

                            <h6>Baranggay List</h6>

                            <div id="assignBrgyHolder" style="height: 40vh; overflow-y:auto">
                                <div class="w-100 d-flex justify-content-center align-items-center flex-column">
                                    <img width="50" height="50"
                                        src="https://img.icons8.com/ios/50/nothing-found.png" alt="nothing-found" />
                                    <h3 class="text-muted">Nothing to show</h3>
                                    <small class="text-muted">Select Zone to show all baranggay list</small>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div id="brgyListDiv" class="w-100">
                        <table id="silayBrgy"
                            class="table table-hover table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                            <thead>
                                <tr>
                                    <th>Baranggay</th>
                                    <th>Assigned Zone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6">
                                        <div style="height: 5vh"
                                            class="d-flex justify-content-center align-items-center">Loading...<img
                                                src="{{ asset('assets/img/loader.gif') }}" class="h-100"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
                <div class="modal-footer border-0">
                    <button type="button" id="addBrgyToZone" class="btn btn-primary d-none">
                        Update Baranggays to the zone
                    </button>
                    <button id="closeUpdateZoneModal"; type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addWayPoints" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Add</span>
                        <span class="fw-light"> Waypoints </span>
                    </h5>
                    <button type="button" id="closeAddWaypoints" class="close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small">
                        Assign a waypoints to each zones
                    </p>

                    <div class="form-group">
                        <label for="selectZoneAddWaypoint">Select Zone</label>
                        <select name="selectZone" id="selectZoneAddWaypoint" class="form-select">
                        </select>
                    </div>

                    <div class="d-none" id="waypointsTableDiv">
                        
                    <table id="waypointsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Waypoint #</th>
                                <th scope="col">Brgy</th>
                                <th scope="col">Place Name</th>
                                <th scope="col">Coordinates</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    </div>



                </div>
                <div class="modal-footer border-0">
                    <button type="button" id="addWaypointToZone" class="btn btn-primary">
                        Edit Zone Waypoints
                    </button>
                    <button id="closeUpdateZoneModal"; type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="assignDriver" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Assign</span>
                        <span class="fw-light"> Driver </span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small">
                        Assign Driver to each zones
                    </p>

                    <div class="form-group">
                        <label class="form-label">Zone List</label>
                        <div class="selectgroup w-100 " id="addDriverZoneList">


                        </div>
                        <small id="noZoneSelected" class="text-danger d-none">You did not select any zone (Operation
                            is invalid)</small>
                    </div>

                    <div class="row d-none" id="selectDriver">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="driverListMain">Assign a driver to this zone</label>
                                <select class="form-select" name="" id="driverListMain">

                                </select>
                                <small id="noMainDriverSelected" class="text-danger d-none">Please Select a main
                                    driver (Operation Invalid)</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="driverListStandby">Assign a standby driver to this zone</label>
                                <select class="form-select" name="" id="driverListStandby">

                                </select>
                                <small id="noStandbyDriverSelected" class="text-danger d-none">Please Select a standby
                                    driver (Operation Invalid)</small>
                            </div>
                        </div>
                    </div>

                    <div id="assignScheduleDiv" class="card mt-4 d-none">
                        <div class="card-header row">
                           <div class="col-6">
                            <div class="card-title">Set Schedule <span>(Optional)</span></div>
                            <small>The default schedule is everyday at 5:00 AM to 03:00 PM if you don't select
                                one</small>
                           </div>

                           <div  class="col-6 d-flex justify-content-end align-items-center ">
                            <button id="manageSchedules" class="btn btn-label-success btn-round d-none"><i class="fa fa-calendar"></i> Manage Schedules</button>
                           </div>
                        </div>

                        <div class="card-body">

                            <div class="form-group">
                                <div class="selectgroup selectgroup-pills">
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="schedDays" value="Mon"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">Mon</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="schedDays" value="Tue"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">Tues</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="schedDays" value="Wed"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">Wed</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="schedDays" value="Thu"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">Thu</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="schedDays" value="Fri"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">Fri</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="schedDays" value="Sat"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">Sat</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="schedDays" value="Sun"
                                            class="selectgroup-input">
                                        <span class="selectgroup-button">Sun</span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group col-6">
                                    <label for="collectionStart">Collection Begins @</label>
                                    <input type="time" id="collectionStart" name="collectionStart"
                                        class="form-control">
                                </div>

                                <div class="form-group col-6">
                                    <label for="collectionEnd">Collection Ends @</label>
                                    <input type="time" id="collectionEnd" name="collectionEnd"
                                        class="form-control">
                                </div>

                            </div>

                        </div>
                    </div>

                    <div id="manageScheduleDiv" class="card mt-4 d-none">
                        <div class="card-header row">
                           <div class="col-6">
                            <div class="card-title">Manage Schedules for Collection </div>
                            <small>Select Certain waypoint reach collection for a certain day or time</small>
                           </div>

                           <div  class="col-6 d-flex justify-content-end align-items-center gap-2">
                            <button id="closeManageSchedule" class="btn btn-label-danger btn-round"><i class="fa fa-times"></i> Cancel</button>
                            <button id="saveManageSchedule" class="btn btn-label-primary btn-round"> <i class="fa fa-save"></i> Save</button>
                           </div>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="selectDayManageSched">Select Schedule Days</label>
                                <select class="form-select" id="selectDayManageSched"></select>
                            </div>

                            <div class="form-group">
                                <label for="selectWaypointManageSched">Waypoint Location</label>
                                <select class="form-select" id="selectWatpointManageSched"></select>
                            </div>

                            <h3 class="mt-3">Schedule List</h3>
                            <table class="table table-bordered ">
                                <thead>
                                  <tr>
                                    <th scope="col">Day</th>
                                    <th scope="col">Waypoint</th>
                                    <th scope="col">Action</th>
                                  </tr>
                                </thead>
                                <tbody id="scheduleTable">
                                    <tr>
                                        <td colspan="3" class="text-center">Loading.....</td>
                                    </tr>
                                </tbody>
                              </table>

                        </div>


                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" id="saveDriverToZone" class="btn btn-primary">
                        Save
                    </button>
                    <button id="closeAssignDriver"; type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @vite('resources/js/app.js')

    @include('Components.script', ['type' => 'admin'])
    <script src="{{ asset('Scripts/route.js') }}"></script>
    <script>
        setTimeout(() => {
            window.Echo.channel('gps-update')
                .listen('GpsUpdate', (e) => {
                    updateRouteStatus(e);
                })
        }, 2000);
    </script>
</body>

</html>
