@extends('layouts.front.master')

@section('title')
Nursing Care
<?php $linkActive = "services"; ?>
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
                        <h3 class="doc-hero-title service-title">Nursing Care</h3>
                        <div class="sub-heading"><div>Incase we dont revert within 2 hours (during working hours), feel free to leave a message on chat and we'd take this up immediately!</div></div>
                    </div>
           	 	</div>
           	 </div> 	 <!-- End Carousel Inner -->

        </div>
  	</section>
    <!--/ Slider end -->

<!-- Service Area start -->

    <section id="service">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <b>Bowel and Bladder Management</b>
                    <p>Not all of us are able to control our bowel and bladder movements due to various factors affecting. There is no need to fear, since we have our home nursing care just for your need. Our well trained nurses provide excellent service to the patients who need their bowel and bladder management to be addressed.</p>

                    <b>Catheter Care</b><p>Urine needs to be drained from the bladder. The catheter helps in draining this urine from the bladder. We provide efficient nursing care for patients who need this assistance. It needs careful instructions to be followed with utmost care; hence our nursing care is the first stop for such patients in India.</p>

                    <b>Ostomy Care</b><p>An Ostomy is a surgery which creates an opening in the body. It is created from inside to outside the body and this opening is called stoma. For patients who have undergone this surgery, we offer nursing care to a speedy on road recovery.</p>

                    <b>Respiratory Care</b><p>Premature babies with underdeveloped lungs to fully developed adults with lung diseases, respiratory care comes as an aid among such patients. Our enabled nurses with excellent trainings will definitely be able to assist the same.</p>

                    <b>Tracheostomy and Ventilator nursing care</b><p>Just undergone a tracheostomy and need care. We have nursing care at your doorstep. Difficulty in breathing and hence on ventilator. Our team of brilliant assistants will be able to guide you further.</p>
                 </div>
            </div>
        </div>  <!-- Container End -->
    </section>
<!-- Service Area End -->

@endsection