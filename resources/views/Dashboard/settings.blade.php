<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Account Settings'])
  </head>
  <body>
    @include('Components.dashload')

    <div class="wrapper">
        @include('Components.nav', ['active'=>'none'])

      <div class="main-panel">
        @include('Components.headernav')

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Account Settings</h3>
                <h6 class="op-7 mb-2">Edit your Account Information</h6>
              </div>

              <div class="ms-md-auto py-2 py-md-0">
                <button id="updateAccount" class="btn btn-primary btn-round d-none"><span class="btn-label">
                    <i class="fa fa-edit"></i> Update Account
                </span> </button>
              </div>

            </div>

            <div class="row bg-white rounded p-2 shadow">
                <div class="col-3 p-4 d-flex flex-column gap-4 justify-content-center align-items-center">
                    <div class="avatar avatar-xxl">
                        <img src="/assets/img/load.jfif" id="accountAvatar" alt="profile" class="avatar-img rounded-circle">
                    </div>
                    <button id="changeProfileModal" data-bs-toggle="modal" data-bs-target="#changeProfilePicture" class="btn btn-secondary"><span class="btn-label">
                    <i class="fas fa-images"></i> Change Profile Pic
                    </span></button>
                </div>
                <form id="accountDetails" class="col-9 row p-4">
                  @csrf
                  <input type="hidden" name="id" id="accountIdSettings">
                    <div class="col-6 d-flex justify-content-center">
                        <div class="form-group">
                            <label for="accountNameSettings">Account Name</label>
                            <input required disabled type="text" name="name" oninput="detectChange()" class="form-control" id="accountNameSettings" value="Loading...." placeholder="Account Name">
                            <small id="accountNameSettingsSpan"  class="form-text text-muted">This is your accounts identity. <span class="text-danger">Note: Avoid Updating it frequently</span>
                              </small>
                          </div>
                    </div>
                    <div class="col-6 d-flex justify-content-center ">
                        <div class="form-group">
                            <label for="accountUsernameSettings">Account Username</label>
                            <input required disabled type="text" name="username" oninput="detectChange()" class="form-control" id="accountUsernameSettings" value="Loading...." placeholder="Account Username">
                            <small id="accountUsernameSettingsSpan" class="form-text text-muted">Updating your username will improve your login credentials.
                              </small>
                          </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#changePassword" class="btn btn-link"><span class="btn-label"><i class="fa fa-asterisk"></i> Change Password</span></button>
                    </div>
                  </form>
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
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title">
            <span class="fw-mediumbold">Change</span>
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
        <form id="changePassForm" class="modal-body">
          @csrf
          <input type="hidden" name="id" id="changePassAccountId">
          <p class="small">
           Change your account password to increase security
          </p>
          <div class="form-group">
            <label for="currentPass">Current Password</label>
            <input type="password" name="currentPass" class="form-control" id="currentPass" placeholder="Current Password">
          </div>
          <div class="form-group">
            <label for="newPass">New Password</label>
            <input type="password" class="form-control" name="newPass" id="newPass" placeholder="New Password">
            <small id="newPassE" class="form-text text-danger d-none">New Password does not match
            </small>
          </div>
          <div class="form-group">
            <label for="confirmPass">Confirm Password</label>
            <input type="password" class="form-control" id="confirmPass" placeholder="Confirm Password">
            <small id="confirmPassE" class="form-text text-danger d-none">New Password does not match
            </small>
          </div>
        </form>
        <div class="modal-footer border-0">
          <button
            type="button"
            id="updatePassword"
            class="btn btn-primary"
            >
            Update Password
          </button>
          <button
          id="closeChangePassword";
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
  id="changeProfilePicture"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">
          <span class="fw-mediumbold">Update</span>
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
      <div class="modal-body p-4  ">
       
        <p class="small">
           Update Accounts Profile Picture for more Identification
           </p>

           <div class="w-full row ">
            <div class="col-3 d-flex justify-content-center align-items-center flex-column">
              <div class="avatar avatar-xxl">
                <img src="/assets/img/load.jfif" alt="changeProfile" id="changeProfileSelected" class="avatar-img rounded-circle">
              </div>
            </div>
            <div class="col-9 border border-1 rounded p-4" id="avatarList">
              
            </div>
            
           </div>
      </div>
      <div class="modal-footer border-0">
        <button
          type="button"
          id="updateProfilePictureBtn"
          class="btn btn-primary"
          >
          Update Profile Picture
        </button>
        <button
        id="closeProfilePicture";
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


<form id="changeProfileForm">
  @csrf
  <input type="hidden" name="id" id="changeProfileAccId">
  <input type="hidden" name="avatar" id="changeProfileSelectedAvatar">

</form>
@include('Components.script', ['type'=> 'admin'])
    <script src="/Scripts/settings.js"></script>
  </body>
</html>
