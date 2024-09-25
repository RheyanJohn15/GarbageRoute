<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Truck Register'])
  </head>
  <body>
    @include('Components.dashload')
    <div class="wrapper">
        @include('Components.nav', ['active'=>'truck'])

      <div class="main-panel">
        @include('Components.headernav')

        <div class="container">
          <div class="page-inner">
            <div
            class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
          >
            <div>
              <h3 class="fw-bold mb-3">Dump Trucks</h3>
              <h6 class="op-7 mb-2">Manage All Trucks in the City</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
              <button style="display: none" id="closeView" onclick="CloseView()" class="btn btn-label-danger btn-round me-2">Close View</button>
              <button data-bs-toggle="modal" data-bs-target="#addTruckModal" class="btn btn-primary btn-round">Add Trucks</button>
            </div>
          </div>

          {{-- Cards Details --}}
          <div id="truckView" class="row px-3" style="display:none">
            <div class="col-md-6">
              <div class="card card-profile">
                <div
                  class="card-header"
                  style="background-image: url('assets/img/blogpost.jpg')"
                >
                  <div class="profile-picture">
                    <div class="avatar avatar-xl">
                      <img id="dumpTruckProfile"
                      src=""
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="user-profile text-center">
                    <div id="dumpTruckModel" class="name"></div>
                    <div id="dumpTruckCanCarry" class="job"></div>
                    <div id="dumpTruckDriver" class="desc"></div>
                    
                    <div class="view-profile">
                      <a href="#" class="btn btn-secondary w-100"
                        >View Full Profile</a
                      >
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          
            <div class="col-md-6">
              <div class="card card-profile">
                <div
                  class="card-header"
                  style="background-image: url('assets/img/blogpost.jpg')"
                >
                  <div class="profile-picture">
                    <div class="avatar avatar-xl">
                      <img id="truckDriverProfile"
                      src=""
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="user-profile text-center">
                    <div id="truckDriverName" class="name"></div>
                    <div id="truckDriverLicense" class="job"></div>
                    <div id="truckDriverAddress" class="desc"></div>
                    
                    <div class="view-profile">
                      <a href="#" class="btn btn-secondary w-100"
                        >View Full Profile</a
                      >
                    </div>
                  </div>
                </div>
               
              </div>
            </div>
            
          </div>


           {{-- Datatable --}}
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Dump Truck List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table
                      id="truckList"
                      class="display table table-striped table-hover"
                    >
                      <thead>
                        <tr>
                          <th>Truck Model</th>
                          <th>Capacity(Tons)</th>
                          <th>Driver</th>
                          <th>Action</thA>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>Truck Model</th>
                          <th>Can Carry</th>
                          <th>Driver</th>
                          <th>Action</th> 
                        </tr>
                      </tfoot>
                      <tbody>
                     
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
      
          

          {{-- Add Truck Modal --}}
     
          <div
          class="modal fade"
          id="addTruckModal"
          tabindex="-1"
          role="dialog"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header border-0">
                <h5 class="modal-title">
                  <span class="fw-mediumbold"> New</span>
                  <span class="fw-light"> Truck </span>
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
                  Create a new truck data
                </p>
                <form id="addTruckForm" method="post">
                  @csrf
                  <div class="row">
                    <div class="col-sm-12">
                      <div id="addModel_g" class="form-group form-group-default">
                        <label>Truck Model</label>
                        <input
                          id="addModel"
                          type="text"
                          name="model"
                          class="form-control"
                          placeholder="Full Name"
                        />
                        <small id="addModel_e" style="display: none" class="text-danger">(This field is required)</small>
                      </div>
                    </div>
                    <div class="col-md-6 pe-0">
                      <div id="addCanCarry_g" class="form-group form-group-default">
                        <label>Can Carry</label>
                        <input
                          id="addCanCarry"
                          name="can_carry"
                          type="text"
                          class="form-control"
                          placeholder="fill username"
                        />
                        <small id="addCanCarry_e" style="display: none" class="text-danger">(This field is required)</small>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div id="addDriver_g" class="form-group form-group-default">
                        <label>Driver</label>
                          <select name="driver" id="addDriver" class="form-select">
                            <option selected disabled value="0">------Select Driver------</options>
                             @php
                         $driver = App\Models\TruckDriverModel::where('td_id', '!=', 0)->get();   
                        @endphp
                        @foreach ($driver as $d)
                            <option value="{{$d->td_id}}">{{$d->name}}</option>
                        @endforeach
                          </select>
                        <small id="addDriver_e" style="display: none" class="text-danger">(This field is required)</small>
                      </div>
                    </div>

                  </div>
                </form>
              </div>
              <div class="modal-footer border-0">
                <button
                  type="button"
                  id="addTruck"
                  class="btn btn-primary"
                  >
                  Add
                </button>
                <button
                  id="closeAddModal"
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

        {{-- Update Truck Modal --}}
        <div
          class="modal fade"
          id="updateTruckModal"
          tabindex="-1"
          role="dialog"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header border-0">
                <h5 class="modal-title">
                  <span class="fw-mediumbold"> Update</span>
                  <span class="fw-light"> Truck </span>
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
                  Update a new truck data
                </p>
                <form id="updateTruckForm" method="post">
                  @csrf
                  <input type="hidden" name="id" id="updateId">
                  <div class="row">
                    <div class="col-sm-12">
                      <div id="updateModel_g" class="form-group form-group-default">
                        <label>Truck Model</label>
                        <input
                          id="updateModel"
                          type="text"
                          name="model"
                          class="form-control"
                          placeholder="Full Name"
                        />
                        <small id="updateModel_e" style="display: none" class="text-danger">(This field is required)</small>
                      </div>
                    </div>
                    <div class="col-md-6 pe-0">
                      <div id="updateCanCarry_g" class="form-group form-group-default">
                        <label>Can Carry</label>
                        <input
                          id="updateCanCarry"
                          name="can_carry"
                          type="text"
                          class="form-control"
                          placeholder="fill username"
                        />
                        <small id="updateCanCarry_e" style="display: none" class="text-danger">(This field is required)</small>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div id="updateDriver_g" class="form-group form-group-default">
                        <label>Driver</label>
                          <select name="driver" id="updateDriver" class="form-select">
                            <option selected disabled value="0">------Select Driver------</options>
                             @php
                         $driver = App\Models\TruckDriverModel::where('td_id', '!=', 0)->get();   
                        @endphp
                        @foreach ($driver as $d)
                            <option value="{{$d->td_id}}">{{$d->name}}</option>
                        @endforeach
                          </select>
                        <small id="updateDriver_e" style="display: none" class="text-danger">(This field is required)</small>
                      </div>
                    </div>

                  </div>
                </form>
              </div>
              <div class="modal-footer border-0">
                <button
                  type="button"
                  id="updateTruck"
                  class="btn btn-primary"
                  >
                  Add
                </button>
                <button
                  id="closeUpdateModal"
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

          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->
    <script src="{{asset('Scripts/dumptruck.js')}}"></script>
    @include('Components.script')
  </body>
</html>
