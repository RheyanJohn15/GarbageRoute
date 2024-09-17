<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'History'])
  </head>
  <body>
    <div class="wrapper">
        @include('Components.userNav', ['active'=>'history'])

      <div class="main-panel">
        @include('Components.userNavHeader')

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">History</h3>
                <h6 class="op-7 mb-2">Previous Routes Finished</h6>
              </div>
            </div>
      
            <div class="table-responsive">
                <table
                  id="basic-datatables"
                  class="display table table-striped table-hover"
                >
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Position</th>
                      <th>Office</th>
                      <th>Age</th>
                      <th>Start date</th>
                      <th>Salary</th>
                    </tr>
                  </thead>
                  <tfoot>
                   
                    
                  </tbody>
                </table>
              </div>
          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->

    @include('Components.script')
  </body>
</html>
