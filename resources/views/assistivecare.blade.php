
@extends('layouts.front.master')

@section('title')
Assistive Care
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
                        <img src="<% asset('static/images/banner/assistive-care.jpg') %>" alt="" class="img-responsive banner_tint">
                    </div>
                    <div class="carousel-caption carousel-new">
                        <h3 class="doc-hero-title service-title">Assistive Care</h3>
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
                        <p><strong>Assistive Care Service (ACS)</strong> is a Medicaid-based, state plan that provides care to eligible recipients who require an integrated set of services on a 24-hour-per-day basis.</p>
                        <p>ACS recipients must demonstrate functional deterioration that makes it medically necessary for them to live in a supportive setting and receive integrated services, whether scheduled or unscheduled. ACS includes:</p>
                        <ol class="numeric-list">
                            <li>Assistance with activities of daily living (ADLs) such as bathing, walking, toileting, etc.</li>
                            <li>Assistance with instrumental activities of daily living such as shopping or making a telephone call.</li>
                            <li>Medication administration and assistance with self-administered medications and</li>
                            <li>Health support observing the recipient’s state of health and well-being on a daily basis and reporting changes to the health care provider as appropriate)</li>
                        </ol>
                    </div>
                    <div class="spacer-20"></div>
                    <article class="col-1">
                    <!--<figure class="align-left-wb"><img src="images/infographic1.png" width="251" height="260" alt="image description" ></figure>-->
                    <h4>Day to Care: Personal Care </h4>
                    <ul class="services service-list">
                        <li class="text-muted">Maintaining Body Hygiene/ Bathing</li>
                        <li class="text-muted">Changing Bed and Bedsheets</li>
                        <li class="text-muted">Avoiding Skin and bed Sores</li>
                        <li class="text-muted">Ensuring Oral Hygiene</li>
                        <li class="text-muted">Urine Incontinence</li>
                        <li class="text-muted">
                            Bowel Incontinence skills,
                            <ul class="service-list">
                                <li class="text-muted">Incontinence and Toileting</li>
                                <li class="text-muted">Assisiting in toileting</li>
                                <li class="text-muted">Controlling stains and odour</li>
                                <li class="text-muted">Empyting the drainage bag</li>
                                <li class="text-muted">Practical demonstration of exercises –Arm/hand; Neck;  Leg/ankle;  Trunk (in bed);  Legs (in bed);  Arms and legs (sitting);  Resistance;  Sitting balance</li>
                            </ul>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <h4>Assistance in activities of daily living</h4>
                    <ul class="services service-list">
                        <li>
                            <strong>Caring for someone in bed:</strong>
                            <ul class="service-list">
                                <li class="text-muted">Adjusting the position of Patient</li>
                                <li class="text-muted">Ensuring the correct body mevhanics while lifting</li>
                                <li class="text-muted">Efficient movement from bed to chair </li>
                            </ul>
                        </li>
                        <li>
                            <strong>Assistive Devices: </strong>
                            <ul class="service-list">
                                <li class="text-muted">Prepare a List</li>
                                <li class="text-muted">
                                    Demonstrate  the skills and use of the equipments – <br>(i) Walker  (ii) Crutches (iii) Others (iv) Patient lift and bathroom equipment (v) Walking aids,Guarding Walk
                                </li>
                                <li class="text-muted">Preventing falls and straining of the back</li>
                                <li class="text-muted">Assiting in wearing footwear</li>
                            </ul>
                        </li>
                    </ul>

                    <div class="clearfix"></div>
                    <h4>Day to Day Care: Food and Nutrition</h4>
                    <p class="text-muted">Knowledge -Nutrition and Hydration. The ways and means to provide healthy food is very critical and we ensure the same.</p>
                    <ul class="services service-list">
                        <li class="strong">Skills :</li>
                        <li class="text-muted">Helping to eat</li>
                        <li class="text-muted">Managing timely medication </li>
                    </ul>

                    <div class="clearfix"></div>
                    <h4>Day to Day Care: Ailments and Medication</h4>
                    <ul class="services service-list">
                        <li class="text-muted">Fever, nausea and vomiting </li>
                        <li class="text-muted">Pain , fatigue, restlessness </li>
                        <li class="text-muted">Anxiety, delirium , depression </li>
                    </ul>

                    <div class="clearfix"></div>
                    <h4>Emotional challenges: Mental health and counselling, handling difficult behavior</h4>
                    <ul class="services service-list">
                        <li class="text-muted">Fatigue, restlessness, anxiety , delirium </li>
                        <li class="text-muted">Depression, social and spiritual distress</li>
                        <li class="text-muted">Dementia, emotional withdrawal </li>
                        <li class="text-muted">End of Life, final Days, when death occurs</li>
                    </ul>
                    </article>

                </div>

        </div>  <!-- Container End -->
    </section>
<!-- Service Area End -->

@endsection