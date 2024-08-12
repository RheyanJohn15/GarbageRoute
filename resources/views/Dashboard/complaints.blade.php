<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Complaints'])
  </head>
  <body>
    <div class="wrapper">
        @include('Components.nav', ['active'=>'complaints'])

      <div class="main-panel">
        @include('Components.headernav')

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Complaints</h3>
                <h6 class="op-7 mb-2">Blank Page</h6>
              </div>
            </div>
      
          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->

    @include('Components.script')
  </body>
</html>
