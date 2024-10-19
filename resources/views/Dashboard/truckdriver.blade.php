<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Truck Driver'])
  </head>
  <body>
    @include('Components.dashload')
    <div class="wrapper">
        @include('Components.nav', ['active'=>'driver'])

      <div class="main-panel">
        @include('Components.headernav')

        <div class="container">
          <div class="page-inner">
            <div
            class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
          >
            <div>
              <h3 class="fw-bold mb-3">Drivers</h3>
              <h6 class="op-7 mb-2">Manage All Trucks Drivers in the City</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
              <button style="display: none" id="closeView" onclick="CloseView()" class="btn btn-label-danger btn-round me-2">Close View</button>
              <button data-bs-toggle="modal" data-bs-target="#addDriverModal" class="btn btn-primary btn-round">Add Driver</button>
            </div>
          </div>

          {{-- Cards Details --}}
          <div id="driverView" class="row px-3" style="display:none">
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
                      <a href="#" id="viewTruckButton" class="btn btn-secondary w-100"
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
                      <a href="#" id="viewDriverButton" class="btn btn-secondary w-100"
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
                  <h4 class="card-title">Truck Drivers List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table
                      id="driverList"
                      class="display table table-striped table-hover"
                    >
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Username</th>
                          <th>License #</th>
                          <th>Contact</th>
                          <th>Address</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>Name</th>
                          <th>Username</th>
                          <th>License #</th>
                          <th>Contact</th>
                          <th>Address</th>
                          <th>Action</thA>
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
      
          
     {{-- Add Modal Driver --}}
          <div
          class="modal fade"
          id="addDriverModal"
          tabindex="-1"
          role="dialog"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header border-0">
                <h5 class="modal-title">
                  <span class="fw-mediumbold"> New</span>
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
                  Create a new truck driver account
                </p>
                <form id="addDriverForm" method="post">
                  @csrf
                  <div class="row">
                    <div class="col-sm-12">
                      <div id="addName_g" class="form-group form-group-default">
                        <label>Name</label>
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
                    <div class="col-md-6 pe-0">
                      <div id="addUsername_g" class="form-group form-group-default">
                        <label>Username</label>
                        <input
                          id="addUsername"
                          name="username"
                          type="text"
                          class="form-control"
                          placeholder="fill username"
                        />
                        <small id="addUsername_e" style="display: none" class="text-danger">(This field is required)</small>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div id="addPassword_g" class="form-group form-group-default">
                        <label>Password</label>
                        <input
                          id="addPassword"
                          type="password"
                          name="password"
                          class="form-control"
                          placeholder="fill password"
                        />
                        <small id="addPassword_e" style="display: none" class="text-danger">(This field is required)</small>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div id="addLicense_g" class="form-group form-group-default">
                        <label>License</label>
                        <input
                          id="addLicense"
                          name="licensenum"
                          type="text"
                          class="form-control"
                          placeholder="fill license number"
                        />
                        <small id="addLicense_e" style="display: none" class="text-danger">(This field is required)</small>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div id="addContact_g" class="form-group form-group-default">
                        <label>Contact #</label>
                        <input
                          id="addContact"
                          type="text"
                          name="contact"
                          class="form-control"
                          placeholder="fill contact #"
                        />
                        <small id="addContact_e" style="display: none" class="text-danger">(This field is required)</small>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div id="addAddress_g" class="form-group form-group-default">
                        <label>Address</label>
                        <input
                          id="addAddress"
                          type="text"
                          name="address"
                          class="form-control"
                          placeholder="fill address"
                        />
                        <small id="addAddress_e" style="display: none" class="text-danger">(This field is required)</small>
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
        </div>

        
      {{-- Update Modal Driver --}}
        <div
        class="modal fade"
        id="updateDriverModal"
        tabindex="-1"
        role="dialog"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header border-0">
              <h5 class="modal-title">
                <span class="fw-mediumbold">Update</span>
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
                Update truck driver account
              </p>
              <form id="updateDriverForm" method="post">
                @csrf
                <input type="hidden" name="id" id="updateId">
                <div class="row">
                  <div class="col-sm-12">
                    <div id="updateName_g" class="form-group form-group-default">
                      <label>Name</label>
                      <input
                        id="updateName"
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Full Name"
                      />
                      <small id="updateName_e" style="display: none" class="text-danger">(This field is required)</small>
                    </div>
                  </div>
                  <div class="col-md-12 pe-0">
                    <div id="updateUsername_g" class="form-group form-group-default">
                      <label>Username</label>
                      <input
                        id="updateUsername"
                        name="username"
                        type="text"
                        class="form-control"
                        placeholder="fill username"
                      />
                      <small id="updateUsername_e" style="display: none" class="text-danger">(This field is required)</small>
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <div id="updateLicense_g" class="form-group form-group-default">
                      <label>License</label>
                      <input
                        id="updateLicense"
                        name="licensenum"
                        type="text"
                        class="form-control"
                        placeholder="fill license number"
                      />
                      <small id="updateLicense_e" style="display: none" class="text-danger">(This field is required)</small>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div id="updateContact_g" class="form-group form-group-default">
                      <label>Contact #</label>
                      <input
                        id="updateContact"
                        type="text"
                        name="contact"
                        class="form-control"
                        placeholder="fill contact #"
                      />
                      <small id="updateContact_e" style="display: none" class="text-danger">(This field is required)</small>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div id="updateAddress_g" class="form-group form-group-default">
                      <label>Address</label>
                      <input
                        id="updateAddress"
                        type="text"
                        name="address"
                        class="form-control"
                        placeholder="fill address"
                      />
                      <small id="updateAddress_e" style="display: none" class="text-danger">(This field is required)</small>
                    </div>
                  </div>
                </div>
              </form>
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

          </div>
        </div>

      </div>

    </div>

    <form method="post" id="deleteDriverForm">
      @csrf
      <input type="hidden" name="id" id="deleteDriver">
    </form>
    <!--   Core JS Files   -->
    <script src="{{asset('Scripts/truckdriver.js')}}"></script>
    @include('Components.script', ['type'=> 'admin'])
  </body>
</html>
