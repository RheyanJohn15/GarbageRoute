<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Admin Dashboard'])
  </head>
  <body>
    @include('Components.dashload')
    <div class="wrapper">
      @include('Components.nav', ['active'=>'dashboard'])

      <div class="main-panel">
        @include('Components.headernav')

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Welcome to Silay Waste Management Information System</h6>
              </div>

            </div>
            <div class="row">
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-primary card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-truck"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Garbage Trucks</p>
                          <h4 id="truckNum" class="card-title">Loading...</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-info card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-user"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Drivers</p>
                          <h4 id="driverNum" class="card-title">Loading...</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-success card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="far fa-check-circle"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Resolved Complaint</p>
                          <h4 id="resolvedComplaintNum" class="card-title">Loading.....</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-secondary card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="far fa-paper-plane"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">Complaints</p>
                          <h4 id="complaintNum" class="card-title">Loading...</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
         
          <div class="row">
            <div class="col-3 card card-info card-annoucement card-round">
              <div class="card-body text-center">
                <div class="card-opening">Total Garbage Collection</div>
              
                <div class="card-detail">
                  <h1> <i class="fas fa-truck-moving"></i> <span id="overAllTotalGarbageCollected">0</span> Tons</h1>
                </div>
                
                <div class="card-desc">
                 <small> This record is an accumulated garbage collected by all of the Drivers
                  registered in the system including those who are re assigned or unassigned to 
                  a zone</small>
                </div>
                <hr>
                <div class="card-detail">
                  <h3>All Zones Collection</h3>
                  <h4> <i class="fas fa-truck-moving"></i> <span id="overAllTotalGarbageCollectedAllZones">0</span> Tons</h4>
                </div>
              </div>
            </div>
            <div class="col-md-9">
              <div class="card">
                <div class="card-header">
                  <div class="card-title">Collected Garbage Per Zones(Tons)</div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="form-group col-6">
                      <label for="garbagePerZoneFilter">Filter By Months</label>
                      <select class="form-select" id="garbagePerZoneFilter">
                        <option value="all">All</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                      </select>
                    </div>
                    <div class="form-group col-6 d-none" id="garbagePerZoneFilterYearsDiv">
                      <label for="garbagePerZoneFilterYears">Select Years</label>
                      <select class="form-select" id="garbagePerZoneFilterYears">
                        <option>2024</option>
                        <option>2025</option>
                        <option>2026</option>
                        <option>2027</option>
                        <option>2028</option>
                        <option>2029</option>
                        <option>2030</option>
                      </select>
                    </div>
                   </div>
                  <div class="chart-container">
                    <canvas id="garbagePerZone"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>

            <div class="card">
              <div class="card-header">
                <div class="card-title">Complaint Chart <small class="text-muted">(Shows how often a specific nature of complaint happens)</small></div>
              </div>
              <div class="card-body row">
                <div class="chart-container col-6"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                  <canvas id="complaintChart" style="width: 429px; height: 300px; display: block;" width="858" height="600" class="chartjs-render-monitor"></canvas>
                </div>
                <div class="chart-container col-6">
                  <canvas id="complaintStatusBarchart"></canvas>
                </div>
              </div>
            </div>

            <div class="">
            
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="card-title">Collectors Total Turnovers</div>
                      </div>
                      <div class="card-body">
                       
                        <div class="chart-container">
                          <canvas id="collectorTotalTurnOver"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                 

                </div>
              </div>
            </div>

          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->

    @include('Components.script', ['type'=> 'admin'])
    <script src="/Scripts/dashboard.js"></script>
  </body>
</html>
