<!DOCTYPE html>
<html lang="en">
  <head>
   @include('Components.header', ['title'=> 'Complaints'])
  </head>
  <body>
    <div class="wrapper">
      @include('Components.dashload')
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
              </div>

              
            </div>
      
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <div class="d-flex align-items-center">
                    <h4 class="card-title">Complaints</h4>
                   
                  </div>
                </div>
                <div class="card-body">
              
                  <div class="table-responsive">
                    <table
                      id="complaint-list"
                      class="display table table-striped table-hover"
                    >
                      <thead>
                        <tr>
                          <th>Complainant</th>
                          <th>Email</th>
                          <th>Zone</th>
                          <th>Date</th>
                          <th>Nature of Complaint</th>
                          <th>Remarks</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                   
                      <tbody>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

          </div>
        </div>

      </div>

    </div>

        {{-- Edit Complaint Modal --}}
    <div
    class="modal fade"
    id="editComplaint"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title">
            <span class="fw-mediumbold"> Update </span>
            <span class="fw-light"> Complaint </span>
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
          Change Status
          </p>
          <form id="updateComplaint" method="post">
            @csrf
            <input type="hidden" name="comp_id" id="comp_id">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group form-group-default">
                  <label>Status</label>
                  <select name="status" class="form-select">
                    <option value="0">Pending</option>
                    <option value="1">In Progress</option>
                    <option value="2">Resolved</option>               
                  </select>
                </div>
              </div>
             
            
            </div>
      
        </div>
        <div class="modal-footer border-0">
          <button
            type="submit"
            class="btn btn-primary"
            >
            Update
          </button>
          <button
          id="closeButton"
            type="button"
            class="btn btn-danger"
            data-bs-dismiss="modal"
          >
            Close
          </button>
        </div>
      </form>
      </div>
    </div>
  </div>
  

  <div
  class="modal fade"
  id="viewComplaint"
  tabindex="-1"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">
          <span class="fw-mediumbold"> View </span>
          <span class="fw-light"> Complaint </span>
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
          View Details
        </p>
     
        <div class="row w-100">
          <div class="col-6">
            <h5>Complainant: <span id="complainant_span"></span><h5>
              <p>Email: <span id="email_span"></span><p>
              <p>Contact: <span id="contact_span"></span><p>
          </div>
          <div class="col-6">
            
          <h5>Nature: <span id="nature_span"></span><h5>
            <p>Date: <span id="date_span"></span><p>
            <p>Status: <span id="status_span"></span><p>
            <p>Zone: <span id="zone_location"></span><p>
          </div>
        </div>



        <p>Remarks</p>
        <div class="border border-1 rounded p-3">
          <p id="remarks_content">
  
          </p>
        </div>

        <div class="w-100 d-flex justify-content-center flex-column gap-4 align-items-center" style="height: 30vh" id="imgLoader">
          <div class="contentLoader"></div>
          <p>Loading Image</p>
        </div>

        <img src="" alt="complaintImage" id="complaintImage" class="w-100 mt-3 rounded d-none">
        
      </div>
      <div class="modal-footer border-0">
    
        <button
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

    <!--   Core JS Files   -->
    <script src="{{asset('Scripts/complaints.js')}}"></script>
    @include('Components.script', ['type'=> 'admin'])
  </body>
</html>
