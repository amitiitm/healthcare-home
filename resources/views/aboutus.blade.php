@extends('layouts.front.master')

@section('title')
About Us
<?php $linkActive = "aboutus"; ?>
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
           	 			<img src="<% asset('static/images/banner/nursing.jpg') %>" alt="" class="img-responsive banner_tint">
           	 		</div>
					<div class="carousel-caption carousel-new">
						<h3 class="doc-hero-title service-title">About Us</h3>
					</div>
           	 	</div>
           	 </div> 	 <!-- End Carousel Inner -->

        </div>
  	</section>
    <!--/ Slider end -->
    <section id="about-us-page-view">
	    <div class="container">
	        <div class="row doc_main_feature">
	            <div class="col-md-12 col-sm-12 col-xs-12 ">
	                <div class="feature_content">
	                     <h2 class="text-center">About Pramati Care</h2>
	                     <div class="divider"></div>


	                     <p class="text-center">Pramati Healthcare Pvt. Ltd. is venture by a team of professionals with a vision to change the way caregiving is provided in India. The founders like to maintain a low profile and prefer their work to take the center stage.
	                         Our Bouquet of Services now includes:</p>

	                         <div class="spacer-5"></div>
	                        <div class="row">
	                            <div class="col-md-4">
	                                <div class="about-us-item">
	                                    <i class="pr icon-occupational-therapist"></i>
	                                    <h3>Assistive Care</h3>
	                                    <p>Through Trained Caregivers (Details below) & Nurses at Home</p>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="about-us-item">
	                                    <i class="pr icon-physiotherapist"></i>
	                                    <h3>Rehabilitation Services</h3>
	                                    <p>Physiotherapists, Speech Therapists and Occupational Therapists at Home</p>
	                                </div>
	                            </div>
	                            <div class="col-md-4">
	                                <div class="about-us-item">
	                                    <i class="pr icon-stethoschope"></i>
	                                    <h3>Value Added Services </h3>
	                                    <p>Medical Equipment, Diagnostic Services at Home</p>
	                                </div>
	                            </div>
	                        </div>

	                </div>



	            </div>  <!-- Col-md-12 End -->
	        </div>
	        <div class="row">
	            <div class="doc_main_feature">
	                <div class="col-md-12 col-xs-12 col-sm-6">
	                        <div class="feature_content">


	                            <h2 class="text-center">What it takes to be a Pramati caregiver</h2>
	            <div class="divider"></div>
	                            A Pramati caregiver is hired after rigorous screening to ensure that she/he has the required patience, acumen and ability to assist care-seekers and the elderly. Once selected, they undergo compulsory training where they are taught the basics of care and nursing. They are also given practical lessons on grooming, communication and handling stress and conflicts.

	                            Training for specific ailments is handled by knowledgeable authorities on the topics. Faculties are drawn from various institutions of importance. The training doesn't end once the caregiver starts out; they are constantly appraised and given relevant classes to deal with cases at hand. They are also regularly updated on new methods of caregiving, including the use of assistive devices to better the quality of Life of the care-seeker.



	                        </div>
	                    </div>

	            </div>
	    </div>  <!-- Row End -->

	    <div class="row doc_main_feature">
	        <div class="col-md-12">
	            <div class="feature_content">
	                <div class=" text-center">
	                    <h2>Our caregivers at a glance</h2>
	                    <div class="divider"></div>

	                    <div class="facts">
	                    <div class="col-md-4 col-xs-12 col-sm-6 columns">
	                        <div class="facts-wrap">
	                         <div class="graph">
	                            <div class="graph-left-side">
	                                <div class="graph-left-container">
	                                    <div class="graph-left-half"> </div>
	                                </div>
	                            </div>
	                            <div class="graph-right-side">
	                                <div class="graph-right-container">
	                                    <div class="graph-right-half"></div>
	                                </div>
	                            </div>
	                            <i class="fa fa-heart-o fa-3x fw"></i>
	                            <div class="facts-wrap-num">
	                                <span class="counter">500</span>+
	                            </div>
	                        </div>

	                        </div>
	                        <h6>Care Givers</h6>
	                    </div>
	                    <div class="col-md-4 col-xs-12 col-sm-6 columns">
	                        <div class="facts-wrap">
	                         <div class="graph">
	                            <div class="graph-left-side">
	                                <div class="graph-left-container">
	                                    <div class="graph-left-half"> </div>
	                                </div>
	                            </div>
	                            <div class="graph-right-side">
	                                <div class="graph-right-container">
	                                    <div class="graph-right-half"></div>
	                                </div>
	                            </div>
	                            <i class="pram icon-nursing fa-3x fw"></i>
	                            <div class="facts-wrap-num"><span class="counter">3500</span>+
	                            </div>
	                         </div>

	                        </div>
	                        <h6>Avg. visits handled/Month</h6>
	                    </div>
	                    <div class="col-md-4 col-xs-12 col-sm-6 columns">
	                        <div class="facts-wrap">
	                         <div class="graph">
	                            <div class="graph-left-side">
	                                <div class="graph-left-container">
	                                    <div class="graph-left-half"> </div>
	                                </div>
	                            </div>
	                            <div class="graph-right-side">
	                                <div class="graph-right-container">
	                                    <div class="graph-right-half"></div>
	                                </div>
	                            </div>
	                            <i class="fa fa-check-square-o fa-3x fw"></i>
	                            <div class="facts-wrap-num"><span class="counter">3500</span>+
	                            </div>
	                            </div>

	                        </div>
	                        <h6>Visits/month</h6>
	                    </div>

	                </div>


	                </div>



	            </div>

	        </div>
	    </div>
	</div>  <!-- Container End -->
    </section>


@endsection