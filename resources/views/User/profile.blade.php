<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Drivers Profile'])
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
                <h3 class="fw-bold mb-3">Driver Profile</h3>
                <h6 class="op-7 mb-2">Drivers Account Profile Information</h6>
              </div>
            </div>
      
            <div class="d-flex w-100 justify-content-center">
              <div class="avatar avatar-xxl">
                <img id="pageProfile" src="/assets/img/loader.gif" alt="..." class="avatar-img rounded-circle">
              </div>

             </div>
             <div class="text-center w-100">
              <h2 id="pageName">Loading....</h2>
              <p>Username: <span id="pageUsername">Loading....</span></p>
              <p>License: <span id="pageLicense">Loading....</span></p>
              <p>Address: <span id="pageAddress">Loading....</span></p>
              <p>Contact: <span id="pageContact">Loading....</span></p>

              <h2 class="mt-2">Assigned Truck</h2>
              <p>Model: <span id="pageModel">Loading.....</span></p>
              <p>Capacity: <span id="pageCapacity">Loading....</span></p>
              <p>Plate Number: <span id="pagePlateNum">Loading....</span></p>
             </div>
           

        </div>

      </div>

    </div>
    <!--   Core JS Files   -->

    @include('Components.script', ['type'=> 'driver'])
    <script src="/Scripts/userProfile.js"></script>
  </body>
</html>
