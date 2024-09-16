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
    {{-- @vite('resources/js/app.js') --}}
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
            <table id="routes"
            class="table table-hover table-bordered table-head-bg-info table-bordered-bd-info mt-4"
          >
            <thead>
              <tr>
                <th>Route Name</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Assigned Driver</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6"><div style="height: 5vh" class="d-flex justify-content-center align-items-center">Loading...<img src="{{asset('assets/img/loader.gif')}}" class="h-100"></div></td>
              </tr>
            </tbody>
          </table>
            <div class="card mt-4" id="addRouteCard">
              <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">Add Route</div>
                <div class="d-flex gap-2">
                  <button class="btn btn-label-danger gap-2 align-items-center" id="clearWaypoints"  style="display: none"> <i class="fas fa-minus-circle"></i>Remove Route</button>
                  
                </div>
              </div>
              <div class="card-body">
                <div class="form-floating form-floating-custom mb-3">
                  <input
                    type="text"
                    class="form-control"
                    id="routeName"
                    placeholder="Route 1"
                  />
                  <label for="routeName">Route Name</label>
                </div>
                <div id="map" class="mb-4" style="height:50vh; width:100%; border: 1px solid black"></div>

                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">Waypoint</th>
                      <th scope="col">Coordinates</th>
                      <th scope="col">Name</th>
                    </tr>
                  </thead>
                 <tbody id="waypointListTable">
                  <tr>
                    <td colspan="3" class="text-center">No Waypoint Added</td>
                  </tr>
                 </tbody>
                </table>

                <label for="selectDriver" class="form-label">Assigned Truck Driver</label>
                <select id="selectDriver" class="form-select">
                  <option value="none" selected disabled>------Select Driver------</option>
                  @php
                      $driver = App\Models\TruckDriverModel::where('td_id', '!=', 0)->get();
                  @endphp
                  @foreach ($driver as $dr)
                      @php
                          $truck = App\Models\DumpTruckModel::where('td_id', $dr->td_id)->first();
                      @endphp
                      <option value="{{$dr->td_id}}">{{$dr->name}} - {{$truck ? $truck->model : "Not Assigned"}}/{{$truck ? $truck->can_carry : 'Not Assigned'}}</option>
                  @endforeach
                </select>

                <div class="form-group">
                  <label class="form-label">Route Schedule</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="schedType"
                        value="daily"
                        class="selectgroup-input"
                        checked=""
                      />
                      <span class="selectgroup-button">Daily</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="schedType"
                        value="weekly"
                        class="selectgroup-input"
                      />
                      <span class="selectgroup-button">Weekly</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="schedType"
                        value="monthly"
                        class="selectgroup-input"
                      />
                      <span class="selectgroup-button">Monthly</span>
                    </label>
                    <label class="selectgroup-item">
                      <input
                        type="radio"
                        name="schedType"
                        value="endsholidays"
                        class="selectgroup-input"
                      />
                      <span class="selectgroup-button">Weekends/Holidays</span>
                    </label>
                  </div>
                </div>

                <div class="w-100 p-4" id="dailySched">
                  <small class="text-primary">Collection every day, ensuring constant service and timely waste removal.</small>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="duration"
                      id="noDurationDaily"
                      value="noDuration"
                      checked
                    />
                    <label
                      class="form-check-label"
                      for="flexRadioDefault1"
                    >
                      No Duration
                    </label>
                  </div>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="duration"
                      id="setDurationDaily"
                      value="setDuration"
                    />
                    <label
                      class="form-check-label"
                      for="flexRadioDefault2"
                    >
                      Set Duration
                    </label>
                  </div>

                  <div class="d-none w-100 p-2 gap-3" id="dailyDuration">
                    <div class="form-group w-100">
                      <label for="startDateDaily">Start Date</label>
                      <input type="date" class="form-control" id="startDateDaily" >
                   
                    </div>
                    <div class="form-group w-100">
                      <label for="endDateDaily">End Date</label>
                      <input type="date" class="form-control" id="endDateDaily">
                    
                    </div>
                  </div>
                </div>

                <div class="w-100 p-4 d-none" id="weeklySched">
                  <div class="form-group">
                    <label class="form-label">Select Days</label>
                    <div class="selectgroup selectgroup-pills">
                      <label class="selectgroup-item">
                        <input
                          type="checkbox"
                          name="weeklyDays"
                          value="1"
                          class="selectgroup-input"
                        />
                        <span class="selectgroup-button">Monday</span>
                      </label>
                      <label class="selectgroup-item">
                        <input
                          type="checkbox"
                          name="weeklyDays"
                          value="2"
                          class="selectgroup-input"
                        />
                        <span class="selectgroup-button">Tuesday</span>
                      </label>
                      <label class="selectgroup-item">
                        <input
                          type="checkbox"
                          name="weeklyDays"
                          value="3"
                          class="selectgroup-input"
                        />
                        <span class="selectgroup-button">Wednesday</span>
                      </label>
                      <label class="selectgroup-item">
                        <input
                          type="checkbox"
                          name="weeklyDays"
                          value="4"
                          class="selectgroup-input"
                        />
                        <span class="selectgroup-button">Thursday</span>
                      </label>
                      <label class="selectgroup-item">
                        <input
                          type="checkbox"
                          name="weeklyDays"
                          value="5"
                          class="selectgroup-input"
                        />
                        <span class="selectgroup-button">Friday</span>
                      </label>
                      <label class="selectgroup-item">
                        <input
                          type="checkbox"
                          name="weeklyDays"
                          value="6"
                          class="selectgroup-input"
                        />
                        <span class="selectgroup-button">Saturday</span>
                      </label>
                      <label class="selectgroup-item">
                        <input
                          type="checkbox"
                          name="weeklyDays"
                          value="7"
                          class="selectgroup-input"
                        />
                        <span class="selectgroup-button">Sunday</span>
                      </label>
                    </div>
                  </div>
                </div>
                </div>

                <div class="w-100 p-4 d-none" id="monthlySched">
                  <div class="form-floating form-floating-custom mb-3">
                    <select class="form-select" id="selectMonthlyOptions" required>
                      <option value="onceAMonth">Once a month</option>
                      <option value="oneWeekPerMonth">A week per month</option>
                      <option value="firstWeek">First Week of the month</option>
                      <option value="lastWeek">Last Week of the month</option>
                      <option value="setCustomDate">Set Custom date</option>
                    </select>
                    <label for="selectMonthlyOptions">Select Monthly Type</label>
                  </div>

                  <div id="setMonthlyCustomDate" class="form-group d-none">
                    <label for="email2">Set Custom Date</label>
                    <input
                      type="date"
                      class="form-control"
                      id="monthlyCustomDate"
                    />
                    <small id="monthlyCustomDate" class="form-text text-muted"
                      >The Waste collection will for this route will be done on the selected date</small
                    >
                  </div>
                </div>

                <div class="w-100 p-4 d-none" id="weekendHolidaySched">
                  <div class="form-floating form-floating-custom mb-3">
                    <select class="form-select" id="selectWeekendHolidays" required>
                      <optgroup label="Weekends">
                        <option>Saturday</option>
                        <option>Sunday</option>
                      </optgroup>
                      <optgroup label="National Holidays">
                        <option>New Year’s Day - January 1</option>
                        <option>Maundy Thursday</option>
                        <option>Good Friday</option>
                        <option>Araw ng Kagitingan (Day of Valor) - April 9</option>
                        <option>Labor Day - May 1</option>
                        <option>Independence Day - June 12</option>
                        <option>National Heroes Day - Last Monday of August</option>
                        <option>Bonifacio Day - November 30</option>
                        <option>Christmas Day - December 25</option>
                        <option>Rizal Day - December 30</option>
                      </optgroup>

                      <optgroup label="Special and Local Holidays">
                        <option>Chinese New Year</option>
                        <option>EDSA People Power Revolution Anniversary - February 25</option>
                        <option>Black Saturday</option>
                        <option>Ninoy Aquino Day - August 21</option>
                        <option>All Saints’ Day - November 1</option>
                        <option>All Souls’ Day - November 2</option>
                        <option>Feast of the Immaculate Conception - December 8</option>
                        <option>Christmas Eve - December 24</option>
                        <option>New Year’s Eve - December 31</option>
                        <option>Sily City Charter Day - December 31</option>
                      </optgroup>
                    </select>
                    <label for="selectFloatingLabel">Select Weekend/Holidays</label>
                  </div>
                </div>

                <div class="d-flex justify-content-end w-100 p-4"><button id="saveRoute" class="btn btn-primary d-flex gap-2 align-items-center"> <i class="fas fa-save"></i> Save</button></div>

              </div>
            </div>
          
          </div>
        </div>

      </div>

    </div>



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
