<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Drivers Account Settings'])
  </head>
  <body>
    @include('Components.dashload')

    <div class="wrapper">
        @include('Components.userNav', ['active'=>' '])

      <div class="main-panel">
        @include('Components.userNavHeader')

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Driver Account Profile</h3>
                <h6 class="op-7 mb-2">Edit your Drivers Account Profile Information</h6>
              </div>
              <div class="ms-md-auto py-2 py-md-0">
                <button data-bs-toggle="modal" data-bs-target="#changePassword" class="btn btn-warning btn-round"><i class="fa fa-asterisk"></i> Change Password</button>
              </div>
            </div>
            
            <div class="row w-100">
              <div class="col-3 d-flex justify-content-center align-items-center flex-column gap-2">
                <div class="avatar avatar-xxl">
                  <img id="profilePicSettings" src="/assets/img/load.jfif" alt="profilePic" class="avatar-img rounded-circle">
                </div>
                <button data-bs-toggle="modal" data-bs-target="#changeProfilePic" class="btn btn-info">
                  <span class="btn-label">
                    <i class="fa fa-camera"></i>
                  </span>
                 Change Profile Pic
                </button>
              </div>

              <form id="driverDetailsForm" class="col-9 row">
                @csrf
                <input type="hidden" name="id" id="driverId">
                <div class="col-6">
                  <div class="form-group">
                    <label for="driverName">Name</label>
                    <input type="text" onclick="changeDetect()" name="name" class="form-control" id="driverName" disabled placeholder="Name" value="Loading....">
                    <small id="driverNameE" class="text-danger d-none">*This field is required*</small>
                  </div>
                  <div class="form-group">
                    <label for="driverLicenseInp">License</label>
                    <input type="text" name="license" onclick="changeDetect()" class="form-control" id="driverLicenseInp" disabled value="Loading...." placeholder="License">
                    <small id="driverLicenseE" class="text-danger d-none">*This field is required*</small>
                  </div>
                  <div class="form-group">
                    <label for="driverAddress">Address</label>
                    <input type="text" name="address" onclick="changeDetect()" class="form-control" id="driverAddress" placeholder="Address" disabled value="Loading...." >
                    <small id="driverAddressE" class="text-danger d-none">*This field is required*</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="driverUsername">Username</label>
                    <input type="text" name="username" onclick="changeDetect()" class="form-control" id="driverUsername" disabled value="Loading...." placeholder="Username">
                    <small id="driverUsernameE" class="text-danger d-none">*This field is required*</small>
                  </div>
                  <div class="form-group">
                    <label for="driverContact">Contact</label>
                    <input type="number" name="contact" onclick="changeDetect()" class="form-control" id="driverContact" value="00000000" disabled placeholder="Contact">
                    <small id="driverContactE" class="text-danger d-none">*This field is required*</small>
                  </div>
          
                </div>
                <div id="updateDetailsDiv" class="w-100 d-flex justify-content-end col-12 d-none">
                  <button type="submit" class="btn d-block btn-success">
                    <span class="btn-label">
                      <i class="fa fa-check"></i>
                    </span>
                   Update Changes
                  </button>
                </div>
              </form>
            </div>

            <hr class="border-4">
            <div class="w-full p-4" id="assignedTruck">
              <h2 id="headerAssigned">Loading.....</h2>
              <div class="row">
                <form id="truckUpdateForm" class="col-3">
                  @csrf
                  <input type="hidden" name="id" id="truckUpdateId">
                  <div class="form-group">
                    <label for="truckModel">Truck Model</label>
                    <input type="text" class="form-control" name="model" id="truckModel" value="Loading...." disabled placeholder="Truck Model">
                    <small id="truckModelE" class="text-danger d-none">*This field is required*</small>
                  </div>
                  <div class="form-group">
                    <label for="truckCapacity">Capacity</label>
                    <input type="text" name="capacity" class="form-control" id="truckCapacity" value="Loading...." disabled placeholder="Truck Capacity">
                    <small id="truckCapacityE" class="text-danger d-none">*This field is required*</small>
                  </div>
                  <button disabled id="saveTruckChanges" class="btn mt-4 ml-2 btn-success"><i class="fa fa-save"></i> Save Changes</button>
                </form>
                <div class="col-9 d-flex justify-content-center align-items-center flex-column p-4">
                  <button data-bs-toggle="modal" data-bs-target="#changeTruckPhoto" class="btn btn-primary"> <i class="fa fa-camera"></i> Upload Truck Photo </button>
                  <img class="rounded w-full" style="height: 40vh" src="/UserPics/Truck/truck-placeholder.jpg" alt="truckImg">
                </div>
              </div>
            </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->

    <div
    class="modal fade"
    id="changePassword"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title">
            <span class="fw-mediumbold"> Change</span>
            <span class="fw-light"> Password </span>
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
           Update your current password to increase security
          </p>
          <form id="changePassForm" class="w-100">
            @csrf
            <input type="hidden" name="id" id="changePassId">
            <div class="form-floating form-floating-custom mb-3">
              <input type="password" name="currentpass" class="form-control" id="currentPassword" placeholder="Current Password">
              <label for="currentPassword">Current Password</label>
              <small id="currentPassE" class="text-danger d-none"></small>
            </div>
            <div class="form-floating form-floating-custom mb-3">
              <input type="password" name="newpass" class="form-control" id="newPassword" placeholder="New Password">
              <label for="newPassword">New Password</label>
              <small id="newPassE" class="text-danger d-none"></small>
            </div>
            <div class="form-floating form-floating-custom mb-3">
              <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password">
              <label for="confirmPassword">Email address</label>
              <small id="confirmPassE" class="text-danger d-none"></small>
            </div>
          </form>
        
        </div>
        <div class="modal-footer border-0">
          <button
            type="button"
            id="changePassBtn"
            class="btn btn-primary"
            >
            Add
          </button>
          <button
          id="closeChangePass";
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
  id="changeProfilePic"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">
          <span class="fw-mediumbold"> Change </span>
          <span class="fw-light"> Profile Picture </span>
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
         Update your Profile Picture
        </p>

        <form id="changeProfilePicForm" class="w-100" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="changeProfileId" name="id">
          <div class="form-group">
            <label for="uploadProfilePic">Upload Profile Pic</label>
            <input type="file" name="pic" class="form-control-file" id="uploadProfilePic">
          </div>

        </form>
      </div>
      <div class="modal-footer border-0">
        <button
          type="button"
          id="changeProfilePicBtn"
          class="btn btn-primary"
          >
          Add
        </button>
        <button
        id="closeChangeProfilePic";
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
id="changeTruckPhoto"
tabindex="-1"
role="dialog"
aria-hidden="true"
>
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header border-0">
      <h5 class="modal-title">
        <span class="fw-mediumbold"> Change </span>
        <span class="fw-light"> Truck Picture </span>
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
       Update your Assigned Truck Image
      </p>

      <form id="uploadTruckImageForm" class="w-100" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="uploadTruckId" name="id">
        <div class="form-group">
          <label for="uploadTruckImage">Upload Truck Image</label>
          <input type="file" name="pic" class="form-control-file" id="uploadTruckImage">
        </div>

      </form>
    </div>
    <div class="modal-footer border-0">
      <button
        type="button"
        id="uploadTruckImageBtn"
        class="btn btn-primary"
        >
        Add
      </button>
      <button
      id="closeUploadTruckImage";
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

  
    @include('Components.script', ['type'=> 'driver'])
    <script src="/Scripts/userSettings.js"></script>
  </body>
</html>
