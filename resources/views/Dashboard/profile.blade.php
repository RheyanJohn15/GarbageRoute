<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Profile'])
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
                <h3 class="fw-bold mb-3">Account Profile</h3>
                <h6 class="op-7 mb-2">View your account Info</h6>
              </div>
            </div>
            <div class="w-100 p-4 rounded mb-4 bg-white shadow d-flex justify-content-center align-items-center flex-column">
              <div class="avatar avatar-xxl">
                <img id="profileAvatar" src="/assets/img/load.jfif" alt="..." class="avatar-img rounded-circle">
              </div>
              <h3 id="profileName">Loading....</h3>
              <p id="profileType">Loading....</p>
            </div>

           <div class="w-full bg-white shadow rounded p-2">
            <div class="w-100 mb-2 p-4 d-flex justify-content-between">
              <h4>All Administrators </h4>
              <button data-bs-toggle="modal" data-bs-target="#addAdminModal" class="btn btn-info btn-round"><span class="btn-label">
              <i class="fa fa-plus"></i>  Add Admin
              </span></button>
            </div>
            <table id="admin-list" class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Username</th>
                  <th scope="col">Type</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
              <tr >
               <td colspan="4">
                <div class="d-flex justify-content-center w-100 gap-2 align-items-center">
                  <p>Loading Admins</p>
                  <img src="/assets/img/loader.gif" style="height: 30px" alt="loader">
                </div>
               </td>
              </tr>
              </tbody>
            </table>
           </div>
          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->

    <div
    class="modal fade"
    id="addAdminModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title">
            <span class="fw-mediumbold">Add</span>
            <span class="fw-light"> Admin </span>
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
        <form id="addAdminForm" class="modal-body p-4  ">
         @csrf
          <p class="small">
            Add additional admin to help manage the system
          </p>
          
          <div class="form-group">
            <label for="adminNameInput">Admin Name</label>
            <input type="text" required name="name" class="form-control" id="adminNameInput" placeholder="Name">
           
          </div>
          <div class="form-group">
            <label for="adminUsernameInput">Admin Username</label>
            <input type="text" required name="username" class="form-control" id="adminUsernameInput" placeholder="Username">
          
          </div>
          <div class="form-group">
            <label for="adminPasswordInput">Admin Password</label>
            <input type="password" name="password" required class="form-control" id="adminPasswordInput" placeholder="Password">
           
          </div>
        </form>
        <div class="modal-footer border-0">
          <button
            type="button"
            id="addAdminBtn"
            class="btn btn-primary"
            >
            Add
          </button>
          <button
          id="closeAddAdminModal";
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
  id="updateAdminModal"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">
          <span class="fw-mediumbold">Update</span>
          <span class="fw-light"> Admin </span>
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
      <form id="updateAdminForm" class="modal-body p-4  ">
       @csrf
        <p class="small">
          Update Admin Information
        </p>
        <input type="hidden" name="id" id="updateAdminId">
        <div class="form-group">
          <label for="updateAdminName">Admin Name</label>
          <input type="text" required disabled value="Loading...." name="name" class="form-control" id="updateAdminName" placeholder="Name">
         
        </div>
        <div class="form-group">
          <label for="updateAdminUsername">Admin Username</label>
          <input type="text" required name="username" value="Loading...." disabled class="form-control" id="updateAdminUsername" placeholder="Username">
        
        </div>
  
      </form>
      <div class="modal-footer border-0">
        <button
          type="button"
          id="updateAdminBtn"
          class="btn btn-primary"
          >
          Add
        </button>
        <button
        id="closeUpdateAdminModal";
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
id="changePassModal"
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
    <form id="changePassForm" class="modal-body p-4  ">
     @csrf
      <p class="small">
        Change Admin Password
      </p>
      <input type="hidden" name="id" id="changePassAdminId">
      <div class="form-group">
        <label for="changePassInput">New Password</label>
        <input type="text" required name="newpass" class="form-control" id="changePassInput" placeholder="New Password">
       
      </div>
    </form>
    <div class="modal-footer border-0">
      <button
        type="button"
        id="changePassBtn"
        class="btn btn-primary"
        >
        Change Password
      </button>
      <button
      id="closeChangePassModal";
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
    @include('Components.script')
    <script src="/Scripts/profile.js"></script>
  </body>
</html>
