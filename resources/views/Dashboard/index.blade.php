<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Admin Dashboard'])
  </head>
  <body>
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
                <h6 class="op-7 mb-2">Blank Page</h6>
              </div>
              {{-- <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                <a href="#" class="btn btn-primary btn-round">Add Customer</a>
              </div> --}}
            </div>
      
          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->

    @include('Components.script')
  </body>
</html>
