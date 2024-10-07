<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Driver Dashboard'])
  </head>
  <style>
    #map {
    width: 100%;
    height: 70vh;
}
  </style>
  <body>
    @include('Components.dashload')

    <div class="wrapper">
        @include('Components.userNav', ['active'=>'dashboard'])

      <div class="main-panel">
        @include('Components.userNavHeader')

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Map & Zone</h3>
                <h6 class="op-7 mb-2">Current Assigned Zone</h6>
              </div>
            </div>
              <ul class="nav nav-tabs nav-line nav-color-secondary" id="line-tab" role="tablist">
                <li class="nav-item submenu" role="presentation">
                  <a class="nav-link" id="line-home-tab" data-bs-toggle="pill" href="#info" role="tab" aria-controls="info" aria-selected="false" tabindex="-1">Zone Assigned Info</a>
                </li>
                <li class="nav-item submenu" role="presentation">
                  <a class="nav-link active" id="line-profile-tab" data-bs-toggle="pill" href="#mapTab" role="tab" aria-controls="map" aria-selected="true">Map Collection</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="line-contact-tab" data-bs-toggle="pill" href="#records" role="tab" aria-controls="records" aria-selected="false" tabindex="-1">Records</a>
                </li>
              </ul>
              <div class="tab-content mt-3 mb-3" id="line-tabContent">
                <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info">
                  <h1 id="infoAssignedZone" class="text-center">Loading...</h1>
        
                  <table id="infoTable" class="table table-hover">
                    <thead>
                      <tr>
                        <th scope="col">Context</th>
                        <th scope="col">Value</th>
                      </tr>
                    </thead>
                    <tbody id="infoTableBody">
                      <tr>
                        <td colspan="2" class="text-center">Loading......</td>
                      </tr>
                    </tbody>
                  </table>
                      
                  </div>
                <div class="tab-pane fade active show" id="mapTab" role="tabpanel" aria-labelledby="map">
                  <button class="btn btn-success w-100 mb-3">Complete Collection</button>
                  <div id="map" style="border: 1px solid black"></div>
                </div>
                <div class="tab-pane fade" id="records" role="tabpanel" aria-labelledby="records">
                  <p>Pityful a rethoric question ran over her cheek, then she continued her way. On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and" and the Little Blind Text should turn around and return to its own, safe country.</p>

                  <p> But nothing the copy said could convince her and so it didnâ€™t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their</p>
                </div>
              </div>
            
          </div>
        </div>

      </div>

    </div>
    <!--   Core JS Files   -->
  
    @include('Components.script', ['type'=> 'driver'])
    <script src="{{asset('Scripts/userDashboard.js')}}"></script>
  </body>
</html>
