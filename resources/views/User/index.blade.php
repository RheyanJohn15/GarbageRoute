<!DOCTYPE html>
<html lang="en">

@include('User.components.head')
<style>
    
strong {
	font-weight: bold; 
}

em {
	font-style: italic; 
}

table {
	background: #f5f5f5;
	border-collapse: separate;
	box-shadow: inset 0 1px 0 #fff;
	font-size: 12px;
	line-height: 24px;
	margin: 30px auto;
	text-align: left;
	width: 100%;
}	

th {
	background: url(https://jackrugile.com/images/misc/noise-diagonal.png), linear-gradient(#777, #444);
	border-left: 1px solid #555;
	border-right: 1px solid #777;
	border-top: 1px solid #555;
	border-bottom: 1px solid #333;
	box-shadow: inset 0 1px 0 #999;
	color: #fff;
  font-weight: bold;
	padding: 10px 15px;
	position: relative;
	text-shadow: 0 1px 0 #000;	
}

th:after {
	background: linear-gradient(rgba(255,255,255,0), rgba(255,255,255,.08));
	content: '';
	display: block;
	height: 25%;
	left: 0;
	margin: 1px 0 0 0;
	position: absolute;
	top: 25%;
	width: 100%;
}

th:first-child {
	border-left: 1px solid #777;	
	box-shadow: inset 1px 1px 0 #999;
}

th:last-child {
	box-shadow: inset -1px 1px 0 #999;
}

td {
	border-right: 1px solid #fff;
	border-left: 1px solid #e8e8e8;
	border-top: 1px solid #fff;
	border-bottom: 1px solid #e8e8e8;
	padding: 10px 15px;
	position: relative;
	transition: all 300ms;
}

td:first-child {
	box-shadow: inset 1px 0 0 #fff;
}	

td:last-child {
	border-right: 1px solid #e8e8e8;
	box-shadow: inset -1px 0 0 #fff;
}	

tr {
	background: url(https://jackrugile.com/images/misc/noise-diagonal.png);	
}

tr:nth-child(odd) td {
	background: #f1f1f1 url(https://jackrugile.com/images/misc/noise-diagonal.png);	
}

tr:last-of-type td {
	box-shadow: inset 0 -1px 0 #fff; 
}

tr:last-of-type td:first-child {
	box-shadow: inset 1px -1px 0 #fff;
}	

tr:last-of-type td:last-child {
	box-shadow: inset -1px -1px 0 #fff;
}	

tbody:hover td {
	color: transparent;
	text-shadow: 0 0 3px #aaa;
}

tbody:hover tr:hover td {
	color: #444;
	text-shadow: 0 1px 0 #fff;
}

    .loader {
    color: #00ff00;
    font-size: 45px;
    text-indent: -9999em;
    overflow: hidden;
    width: 1em;
    height: 1em;
    border-radius: 50%;
    position: relative;
    transform: translateZ(0);
    animation: mltShdSpin 1.7s infinite ease, round 1.7s infinite ease;
  }
  
  @keyframes mltShdSpin {
    0% {
      box-shadow: 0 -0.83em 0 -0.4em,
      0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em,
      0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
    }
    5%,
    95% {
      box-shadow: 0 -0.83em 0 -0.4em, 
      0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 
      0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
    }
    10%,
    59% {
      box-shadow: 0 -0.83em 0 -0.4em, 
      -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, 
      -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
    }
    20% {
      box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em,
       -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, 
       -0.749em -0.34em 0 -0.477em;
    }
    38% {
      box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em,
       -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, 
       -0.82em -0.09em 0 -0.477em;
    }
    100% {
      box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 
      0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
    }
  }
  
  @keyframes round {
    0% { transform: rotate(0deg) }
    100% { transform: rotate(360deg) }
  }
   
</style>
<body>
    @include('Components.dashload')
    @include('User.components.preloader')
    @include('User.components.header')

    <div class="main-banner" id="top">
        <div class="container">
            <div class="row p-0">
                <div class="col-lg-12 me-4">
                    <div class="row">
                        <div class="col-lg-6 ms-4">

                            <div class="owl-carousel owl-banner" style="margin-top: -15%; margin-left: -11%">
                                <div class="item header-text">
                                    <p style="color: rgb(0, 83, 0); font-size:30px; font-weight: bold">Silay City
                                        Garbage <span style="color: rgb(0, 67, 105)"> Complaint Form</span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <form id="contact" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <fieldset>
                                                <input type="text" oninput="autoCaps(this); nameClean(this)" name="comp_name" id="comp_name"
                                                    placeholder="Full Name" required>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-12">
                                            <fieldset>
                                                <input oninput="autoCaps(this)" type="email" name="email" id="email"
                                                    pattern="[^ @]*@[^ @]*" placeholder="Your Email">
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-12">
                                            <fieldset>
                                                <input type="text" name="contact" id="contacts"
                                                    placeholder="Contact" maxlength="11" required>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-12 mb-2 mt-2">
                                            <fieldset>
                                                <select class="form-select" name="nature" id="nature" required>
                                                    <option value="" disabled selected> Select Nature of Complaint
                                                    </option>
                                                    <option>Missed Collection</option>
                                                    <option>Late Irregular Service</option>
                                                    <option>Improper Handling of Waste</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-12 mb-2 mt-2">
                                            <fieldset>
                                                <select disabled id="zonelist" class="form-select" name="zone" required>
                                                    <option>Loading......</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-12 mb-4 mt-2">
                                            <fieldset>
                                                <textarea oninput="autoCaps(this)" class="form-control" id="message" name="remarks" placeholder="Message/Remarks" rows="5"></textarea>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-12 mb-4">
                                            <label for="attachment" class="form-label">Attach a Photo:</label>
                                            <input type="file" class="form-control" name="attachment" id="attachment"
                                                accept=".jpg,.jpeg,.png" onchange="previewImage(event)">

                                            <div id="imagePreview" class="mt-2" style="display:none;">
                                                <img id="preview" src="" alt="Image Preview" class="img-fluid"
                                                    style="width: 200px; height: 200px; object-fit: cover;" />

                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <fieldset>
                                                <button type="submit" id="form-submit" class="main-button">Submit
                                                    Complaint</button>
                                            </fieldset>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- <div class="owl-carousel owl-banner">
                                <div class="item header-text">
                                    <h6>Welcome to SCWMS</h6>
                                    <h2>For a <em>Cleaner</em>, and <span style="color: green">Greener</span> World</h2>
                                    <p>A platform dedicated to empowering residents and businesses to contribute to a
                                        sustainable Silay City.</p>
                                    <div class="down-buttons">
                                        <div class="main-blue-button-hover">
                                            <a href="#contact">Contact Us Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="services" class="our-services section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-heading">
                        <h2 style="font-size: 40px"> Community <em> Complaints </em> Overview </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="w-100 d-none justify-content-center align-items-center flex-column gap-4" id="emptyCpl">
                        <img src="/assets/images/empty.png" class="w-25" alt="empty">
                        <h2>No <em>Complaints</em></h2>
                    </div>
                    <div style="height: 40vh" id="compLoader" class="w-100 d-flex justify-content-center flex-column gap-4 align-items-center">
                        <div class="loader"></div>
                        <p>Please Wait.......</p>
                    </div>
                    <div class="owl-carousel owl-services d-none" id="complaintList">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-dec" style="margin-top: -5%">
        <img src="assets/images/footer-dec.png" alt="">
    </div>

    <div id="about" class="about-us section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <div class="left-image">
                        <img src="assets/images/about-left-image.png" alt="Two Girls working together">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-heading">
                        <h2>Track Your Community's <em>Complaints</em> &amp; <span>Resolutions</span> Efficiently</h2>
                        <p>Monitor complaint trends and actions taken to ensure timely resolution and improve community
                            services in Silay City.</p>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="fact-item">
                                    <div class="count-area-content">
                                        <div class="icon">
                                            <i class="fas fa-clock icon-large"></i>
                                        </div>
                                        <div id="pendingCounter" class="count-digit">0</div>
                                        <div class="count-title">Pending</div>
                                        <p>Complaints received and awaiting action.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="fact-item">
                                    <div class="count-area-content">
                                        <div class="icon">
                                            <i class="fas fa-gears icon-large"></i>
                                        </div>
                                        <div id="progressCounter" class="count-digit">0</div>
                                        <div class="count-title">Scheduled for Action</div>
                                        <p>Issues identified and actions scheduled.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="fact-item">
                                    <div class="count-area-content">
                                        <div class="icon">
                                            <i class="fas fa-check-circle icon-large"></i>
                                        </div>
                                        <div id="resolveCounter" class="count-digit">0</div>
                                        <div class="count-title">Resolved</div>
                                        <p>Complaints resolved with actions taken.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="schedules" class="contact-us section">
        <div class="container" style="padding-left: 15px; padding-right: 15px;">
            <div class="section-heading text-center">
                <h2 style="font-size: 40px !important">
                    <em>Truck Drivers</em> Collection <span style="font-size: 40px !important">Schedules </span>
                </h2>
            </div>

            <table>
                <thead>
                  <tr>
                    <th>Zone</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                    <th>Sunday</th>
                  </tr>
                </thead>
                <tbody id="driverSchedules">
                  
                </tbody>
              </table>

        </div>
    </div>
    <div id="mapview" class="contact-us section">
        <div class="container" style="padding-left: 15px; padding-right: 15px;">
            <div class="section-heading text-center">
                <h2 style="font-size: 40px !important">Sector Map of
                    <em>Silay City</em> Garbage <span style="font-size: 40px !important">Collection Routes </span>
                </h2>
            </div>
            <div class="row mb-4 px-0">
                <!-- Map Row -->
                <div class="col-lg-12">
                    <div id="webMap">
                        
                    </div>
                </div>
            </div>

            <div class="row px-0" style="margin-left: 8%">
                {{-- <div class="section-heading text-center mt-4">
                    <h2 style="font-size: 40px !important">Silay Waste
                        <em>Management</em> Complaint <span style="font-size: 40px !important">Form</span>
                    </h2>
                </div> --}}
                {{-- <!-- Form Row -->
                <div class="col-lg-12">
                    <form id="contact" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <fieldset>
                                    <input type="text" name="comp_name" id="comp_name" placeholder="Full Name"
                                        required>
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*"
                                        placeholder="Your Email" required>
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <input type="text" name="contact" id="contacts" placeholder="Contact"
                                        required>
                                </fieldset>
                            </div>
                            <div class="col-lg-12 mb-2 mt-2">
                                <fieldset>
                                    <select class="form-select" name="nature" id="nature" required>
                                        <option value="" disabled selected> Select Nature of Complaint</option>
                                        <option>Missed Collection</option>
                                        <option>Late Irregular Service</option>
                                        <option>Improper Handling of Waste</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-lg-12 mb-4 mt-2">
                                <fieldset>
                                    <textarea class="form-control" id="message" name="remarks" placeholder="Message/Remarks" rows="5" required></textarea>
                                </fieldset>
                            </div>
                            <div class="col-lg-12 mb-4">
                                <label for="attachment" class="form-label">Attach a Photo (Optional):</label>
                                <input type="file" class="form-control" name="attachment" id="attachment"
                                    accept=".jpg,.jpeg,.png" onchange="previewImage(event)">

                                <div id="imagePreview" class="mt-2" style="display:none;">
                                    <img id="preview" src="" alt="Image Preview" class="img-fluid"
                                        style="width: 200px; height: 200px; object-fit: cover;" />

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <button type="submit" id="form-submit" class="main-button">Submit
                                        Complaint</button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div> --}}
            </div>
        </div>

        <div class="contact-left-dec">
            <img src="assets/images/contact-left-dec.png" alt="">
        </div>
    </div>

    <div class="footer-dec" style="margin-top: -5%">
        <img src="assets/images/footer-dec.png" alt="">
    </div>


    @include('User.components.footer')

</body>


@include('User.components.scripts')

<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const preview = document.getElementById('preview');

        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            imagePreview.style.display = 'block';
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    }
</script>
@vite('resources/js/app.js')

<script src="{{ asset('assets/complaints.js') }}"></script>
<script>
    setInterval(async () => {
        const response = await fetch('/api/getactivedriver');

        const result = await response.json();

        updateRouteStatus(result);
    }, 1000);
</script>

<script src="{{ asset('Scripts/helper.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</html>
