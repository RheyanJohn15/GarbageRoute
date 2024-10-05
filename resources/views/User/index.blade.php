<!DOCTYPE html>
<html lang="en">

@include('User.components.head')

<body>
    @include('Components.dashload')
    @include('User.components.preloader')
    @include('User.components.header')

    <div class="main-banner" id="top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 me-4">
                    <div class="row">
                        <div class="col-lg-6 align-self-center">
                            <div class="owl-carousel owl-banner">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="services" class="our-services section">
        <div class="services-right-dec">
            <img src="assets/images/services-right-dec.png" alt="">
        </div>
        <div class="container">
            <div class="services-left-dec">
                <img src="assets/images/services-left-dec.png" alt="">
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2 style="font-size: 40px">Sustainable <em> Waste </em> Management <span> Solutions</span></h2>
                        <span>Our Services</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel owl-services">

                        <div class="item">
                            <h4>Waste Segregation</h4>
                            <div class="icon"><i class="fas fa-recycle icon-large"></i></div>
                            <p>Promotes proper segregation of waste into biodegradable, non-biodegradable, and
                                recyclable categories.</p>
                        </div>
                        <div class="item">
                            <h4>Waste Collection</h4>
                            <div class="icon"><i class="fas fa-trash icon-large"></i></div>
                            <p>Regular collection of waste from households and businesses, ensuring timely transport to
                                disposal sites.</p>
                        </div>
                        <div class="item">
                            <h4>Recycling Programs</h4>
                            <div class="icon"><i class="fas fa-recycle icon-large"></i></div>
                            <p>Encourages recycling initiatives by setting up materials recovery facilities to process
                                recyclable waste.</p>
                        </div>
                        <div class="item">
                            <h4>Solid Waste Disposal</h4>
                            <div class="icon"><i class="fas fa-dumpster icon-large"></i></div>
                            <p>Safe disposal of solid waste at designated landfills, ensuring compliance with
                                environmental standards.</p>
                        </div>
                        <div class="item">
                            <h4>Waste Management</h4>
                            <div class="icon"><i class="fas fa-exclamation-triangle icon-large"></i></div>
                            <p>Special handling and disposal of hazardous waste materials to prevent contamination.</p>
                        </div>
                        <div class="item">
                            <h4>Public Education Campaigns</h4>
                            <div class="icon"><i class="fas fa-book-open icon-large"></i></div>
                            <p>Initiatives aimed at educating the public on sustainable waste management practices.</p>
                        </div>
                        <div class="item">
                            <h4>Climate Change Adaptation</h4>
                            <div class="icon"><i class="fas fa-globe-americas icon-large"></i></div>
                            <p>Integrates climate adaptation strategies in waste management to reduce the environmental
                                impact.</p>
                        </div>
                        <div class="item">
                            <h4>Flood Mitigation</h4>
                            <div class="icon"><i class="fas fa-water icon-large"></i></div>
                            <p>Addresses waste-related issues that contribute to flooding and landslides in vulnerable
                                areas.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
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
                        <h2>Grow Your Community with Our <em>Waste</em> &amp; <span>Management</span> Solutions</h2>
                        <p>Explore effective waste management services designed to promote sustainability and protect
                            the environment in Silay City.</p>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="fact-item">
                                    <div class="count-area-content">
                                        <div class="icon">
                                            <i class="fas fa-recycle icon-large"></i>
                                        </div>
                                        <div class="count-digit">320</div>
                                        <div class="count-title">Waste Management Initiatives</div>
                                        <p>Reducing waste and enhancing recycling efforts.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="fact-item">
                                    <div class="count-area-content">
                                        <div class="icon">
                                            <i class="fas fa-users icon-large"></i>
                                        </div>
                                        <div class="count-digit">640</div>
                                        <div class="count-title">Community Engagement</div>
                                        <p>Educating residents on sustainable practices.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="fact-item">
                                    <div class="count-area-content">
                                        <div class="icon">
                                            <i class="fas fa-handshake icon-large"></i>
                                        </div>
                                        <div class="count-digit">120</div>
                                        <div class="count-title">Successful Partnerships</div>
                                        <p>Collaborating for a cleaner, greener community.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div id="contact" class="contact-us section">
        <div class="container" style="padding-left: 15px; padding-right: 15px;">
            <div class="section-heading text-center">
                <h2 style="font-size: 40px !important">Sector Map of
                    <em>Silay City</em> Garbage <span style="font-size: 40px !important">Collection Routes </span>
                </h2>
            </div>
            <div class="row mb-4 px-0">
                <!-- Map Row -->
                <div class="col-lg-12">
                    <div id="map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d250862.38945540946!2d122.92553509623687!3d10.755677339277627!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33aed61aecc67287%3A0x420b7cea0efc5e27!2sSilay%20City%2C%20Negros%20Occidental!5e0!3m2!1sen!2sph!4v1727185458669!5m2!1sen!2sph"
                            width="100%" height="500px" frameborder="0" style="border:0"
                            allowfullscreen=""></iframe>
                    </div>
                </div>
            </div>

            <div class="row px-0" style="margin-left: 8%">
                <div class="section-heading text-center mt-4">
                    <h2 style="font-size: 40px !important">Silay Waste
                        <em>Management</em> Complaint <span style="font-size: 40px !important">Form</span>
                    </h2>
                </div>
                <!-- Form Row -->
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
                </div>
            </div>
        </div>

        <div class="contact-dec">
            <img src="assets/images/contact-dec.png" alt="">
        </div>
        <div class="contact-left-dec">
            <img src="assets/images/contact-left-dec.png" alt="">
        </div>
    </div>

    <div class="footer-dec">
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

<script src="{{ asset('assets/complaints.js') }}"></script>
<script src="{{ asset('Scripts/helper.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</html>
