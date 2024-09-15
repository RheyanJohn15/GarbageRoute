<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Complaint Form</title>
    <link rel="stylesheet" href="{{asset('assets/complaint.css')}}">
    <script src="https://kit.fontawesome.com/ccaf8ead0b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
</head>
<body>
    @include('Components.dashload')
<div class="container">
    <!-- Title of the form -->
    <h1 class="title-header">Silay Waste Management Complaint Form</h1>

    <form id="complaintForm">
        @csrf
      <div class="row">
        <h4>Complainant</h4>
        <div class="input-group input-group-icon">
          <input required type="text" name="comp_name" placeholder="Full Name"/>
          <div class="input-icon"><i class="fa fa-user"></i></div>
        </div>
        <div class="input-group input-group-icon">
          <input required type="email" name="email" placeholder="Email Address"/>
          <div class="input-icon"><i class="fa fa-envelope"></i></div>
        </div>
        <div class="input-group input-group-icon">
          <input required type="text" name="contact" type="number" placeholder="Contact"/>
          <div class="input-icon"><i class="fa fa-phone"></i></div>
        </div>
      </div>

   

      <div class="row">
        <h4>Complaint</h4>

        <div class="row">
            <select name="nature" required class="w-full">
                <option value="" disabled selected>--------Select Nature of Complaint---------</option>
                <option>Missed Collection</option>
                <option>Late Irregular Service</option>
                <option>Improper Handling of Waste</option>
                <option>Overfilled Bins or Dumpsters</option>
                <option>Unclean Service</option>
                <option>Noise Complaints</option>
                <option>Missorted Waste</option>
                <option>Non-compliance with Special Waste Services</option>
                <option>Bin Request or Replacement Issue</option>
                <option>Unpleasant Odor</option>
                <option>Route Issue</option>
                <option>Poor Customer Service</option>
              </select>
          </div>


        <div class="input-group mt-4">
            <textarea required id="message" name="remarks" placeholder="Message/Remarks" class="textarea-custom" rows="5"></textarea>
        </div>
      </div>

      <div class="w-full flex justify-end">
        <button type="submit" class="button-custom">Submit Complaint</button>
      </div>

    </form>
  </div>
  <script src="{{asset('assets/js/core/jquery-3.7.1.min.js')}}"></script>


  <script src="{{asset('assets/complaints.js')}}"></script>
  <script src="{{asset('Scripts/helper.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</body>
</html>
