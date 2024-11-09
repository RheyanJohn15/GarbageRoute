<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Activity Logs'])
  </head>
  <body>
    @include('Components.dashload')
    <div class="wrapper">
        @include('Components.nav', ['active'=>'activity'])

      <div class="main-panel">
        @include('Components.headernav')

        <div class="container">
          <div class="page-inner">
            <div
            class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
          >
            <div>
              <h3 class="fw-bold mb-3">Activity Logs</h3>
            </div>

          </div>

          <table id="tableLogs" class="table table-head-bg-success">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Handle</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>

          </div>
        </div>

      </div>

    </div>

    <script src="/Scripts/activitylogs.js"></script>
    @include('Components.script', ['type'=> 'admin'])
  </body>
</html>
