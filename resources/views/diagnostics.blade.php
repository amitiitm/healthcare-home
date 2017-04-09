@extends('layouts.front.master')

@section('title')
Diagnostics At Home
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
                        <img src="<% asset('static/images/banner/diagnostics.jpg') %>" alt="" class="img-responsive banner_tint">
                    </div>
                    <div class="carousel-caption carousel-new">
                        <h3 class="doc-hero-title service-title">Diagnostics at Home</h3>
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
                    <b>Diagnostics at home</b>
                    <p>Get all your diagnostics done in comfort of your home. We carry everything we need for our diagnostic services.</p>
                </div>
            </div>
        </div>  <!-- Container End -->
    </section>
<!-- Service Area End -->

@endsection