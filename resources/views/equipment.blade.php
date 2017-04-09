@extends('layouts.front.master')

@section('title')
Equipments
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
           	 			<img src="<% asset('static/images/banner/equipment.jpg') %>" alt="" class="img-responsive banner_tint">
           	 		</div>
                    <div class="carousel-caption carousel-new">
                        <h3 class="doc-hero-title service-title">Equipments</h3>
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
                   <b>Equipments on Rent</b>
                   <p>Medical Equipments cost quite a lot of money these days. Ensuring the patients get attention all the time and are taken care off, we offer a unique facility of renting equipments. Use it, Return it!</p>
               </div>
           </div>
        </div>  <!-- Container End -->
    </section>
<!-- Service Area End -->

@endsection