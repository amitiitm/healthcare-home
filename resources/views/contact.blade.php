@extends('layouts.front.master')

@section('title')
Contact
<?php $linkActive = "contactus"; ?>
@endsection

@section('content')

    <section id="doc_slider_part">
             <div class="carousel slide" id="carousel-example-generic" data-ride="carousel">
                <!-- Indicators -->
             	 <!--<ol class="carousel-indicators text-center">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                 </ol>-->

               	<div class="carousel-inner">
               	 	<div class="item active">
               	 		<div class="doc-overlay-slide">
               	 			<img src="<% asset('static/images/banner/contact-us.jpg') %>" alt="" class="img-responsive">
               	 		</div>
                        <div class="carousel-caption carousel-new">
                            <h3 class="doc-hero-title service-title">Contact Us</h3>

                        </div>
               	 	</div>
               	 </div> 	 <!-- End Carousel Inner -->

            </div>
      	</section>
    <!--/ Slider end -->
    <section ng-controller="contactCtrl" id="contact-page-view">
	    <div class="container">
	        <div class="spacer-5"></div>
	        <div class="row">
	            <div class="col-md-12 col-sm-12 col-xs-12">
					<div class="feature_content">
                         <h2 class="text-center">Get In Touch</h2>
                         <div class="divider"></div>
                    </div>
	            </div>  <!-- Col-md-12 End -->
	        </div>
	        <div class="row">
	            <div class="doc_main_feature">
	                <div class="col-md-6 col-xs-12 col-sm-12">
	                    <h3 class="menu_head">Leave us a message</h3>
	                    <form>
	                          <div class="alert alert-success no-margin ng-hide" ng-show="submittedSuccess">
	                            <span>Thank you for submitting your request.</span>
	                          </div>
	                          <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="text" ng-model="contactData.name" class="form-control" id="contactInputName" placeholder="Name" tooltip-class="tooltip-error" uib-tooltip="{{contactFormValidation.name.message}}" tooltip-is-open="!contactFormValidation.name.valid" tooltip-trigger="none" tooltip-placement="bottom-left" >
                              </div>
	                          <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" ng-model="contactData.email" class="form-control" id="contactInputEmail" placeholder="Email" tooltip-class="tooltip-error" uib-tooltip="{{contactFormValidation.email.message}}" tooltip-is-open="!contactFormValidation.email.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                              </div>
	                          <div class="form-group">
                                <label for="exampleInputEmail1">Contact Number</label>
                                <input type="text" ng-model="contactData.phone" class="form-control" id="contactInputPhone" placeholder="Contact No" tooltip-class="tooltip-error" uib-tooltip="{{contactFormValidation.phone.message}}" tooltip-is-open="!contactFormValidation.phone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                              </div>
	                          <div class="form-group">
                                <label for="contactInputMessage">Message</label>
                                <textarea class="form-control" ng-model="contactData.message"></textarea>
                              </div>
                              <div class="form-group">
                                <button class="btn btn-default pull-right" ng-click="submitMessage()">Submit</button>
                              </div>
	                    </form>
	                </div>
	                <div class="col-md-6 col-sm-6 col-xs-12">
	                    <h3 class="menu_head">Contact Details</h3>
	                        <ul class="contact-address">
	                            <li> <i class="fa fa-home"></i>
	                                <span> E-7 2<sup>nd</sup> Floor Sector-3 Noida-201301 </span></li>
	                            <li><i class="fa fa-phone"></i>
	                                <span> +91-8010667766</span></li>
	                            <li><i class="fa fa-globe"></i>
	                                <span> www.pramaticare.com</span></li>
	                            <li><i class="fa fa-envelope"></i>
	                                <span>  info@Pramaticare.com </span></li>
	                        </ul>
	                    </div>

	            </div>
		    </div>  <!-- Row End -->
		</div>  <!-- Container End -->
    </section>
    <div class="spacer-20"></div>
    <section>
	    <div id="g-map" class="no-padding">
	        <div class="container-fluid">
	            <div class="row">
	                <div class="map" id="map"></div>
	            </div>
	        </div>
	    </div>
    </section>
@endsection

@section('pageJS')
<script type="text/javascript" src="<% asset('/static/js/gmaps.js') %>"></script>
        <script>
            // Google Map - with support of gmaps.js
            var map;
            map = new GMaps({
              div: '#map',
              lat: 28.581183,
              lng: 77.317638,
              scrollwheel: false,
              panControl: false,
              zoomControl: true
            });

            map.addMarker({
              lat: 28.581183,
              lng: 77.317638,
              title: 'Pramati Healthcare Pvt Ltd',
              infoWindow: {
                content: '<p><strong>Pramati Healthcare Pvt Ltd</strong><br />Block E, Sector 3, Noida, Uttar Pradesh 201301</p>'
              },
              icon: baseUrl+"/static/images/map.png"
            });
        </script>
@endsection

