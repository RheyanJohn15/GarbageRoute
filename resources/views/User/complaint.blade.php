<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Complaint Form</title>
    <link rel="stylesheet" href="{{asset('assets/complaint.css')}}">
    <script src="https://kit.fontawesome.com/ccaf8ead0b.js" crossorigin="anonymous"></script>
</head>
<body>
    
<div class="container">
    <!-- Title of the form -->
    <h1 class="title-header">Silay Waste Management Complaint Form</h1>

    <form>
      <div class="row">
        <h4>Complainant</h4>
        <div class="input-group input-group-icon">
          <input type="text" placeholder="Full Name"/>
          <div class="input-icon"><i class="fa fa-user"></i></div>
        </div>
        <div class="input-group input-group-icon">
          <input type="email" placeholder="Email Address"/>
          <div class="input-icon"><i class="fa fa-envelope"></i></div>
        </div>
        <div class="input-group input-group-icon">
          <input type="text" placeholder="Contact"/>
          <div class="input-icon"><i class="fa fa-phone"></i></div>
        </div>
      </div>

   

      <div class="row">
        <h4>Complaint</h4>

        <div class="row">
            <select class="w-full">
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
            <textarea id="message" placeholder="Message/Remarks" class="textarea-custom" rows="5"></textarea>
        </div>
      </div>

      <div class="w-full flex justify-end">
        <button type="button" class="button-custom">Submit Complaint</button>
      </div>

    </form>
  </div>
  <script src="{{asset('assets/complaints.js')}}"></script>
</body>
</html>
