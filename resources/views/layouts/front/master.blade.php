 <!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
    <!-- Basic Page Needs
    ================================================== -->
        <meta charset="utf-8">
        <title>PramatiCare - @yield('title')</title>
        <meta name="description" content="">
        <!-- Mobile Specific Metas
    ================================================== -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
         <!-- CSS
         ================================================== -->
         <link href="<% asset('icon.png') %>" rel="icon">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<% asset('static/css/bootstrap.min.css') %>"/>
        <!-- FontAwesome -->
        <link rel="stylesheet" href="<% asset('static/css/font-awesome.min.css') %>"/>
        <!-- Animation -->
        <link rel="stylesheet" href="<% asset('static/css/animate.css') %>" />
        <!-- Owl Carousel -->
        <link rel="stylesheet" href="<% asset('static/css/owl.carousel.css') %>"/>
        <link rel="stylesheet" href="<% asset('static/css/owl.theme.css') %>"/>
        <!-- Pretty Photo -->
        <link rel="stylesheet" href="<% asset('static/css/prettyPhoto.css') %>"/>
        <!-- Main color style -->
        <link rel="stylesheet" href="<% asset('static/css/red.css') %>"/>
        <!-- Template styles-->

        <!-- Responsive -->

        <link rel="stylesheet" href="<% asset('static/css/jquery.fancybox.css?v=2.1.5') %>" type="text/css" media="screen" />

		<!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>-->
		<script type="text/javascript">
			var baseUrl = '<?php echo url(); ?>';
		</script>
        <link href='http://fonts.googleapis.com/css?family=Lato:400,300' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Open Sans:400,300,500' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/0.11.2/angular-material.min.css">
        <link rel="stylesheet" href="<% asset('static/vendors/google-autocomplete/autocomplete.min.css') %>">

		<link rel="stylesheet" type="text/css" href="<% asset('static/vendors/slick/slick.css') %>"/>
		<link rel="stylesheet" type="text/css" href="<% asset('static/vendors/slick/slick-theme.css') %>"/>

        <link rel="stylesheet" href="<% asset('static/css/pramati-font.css') %>" />
        <link rel="stylesheet" href="<% asset('static/css/custom.css') %>" />
        <link rel="stylesheet" href="<% asset('static/css/frontmaterial.css') %>" />
        <link rel="stylesheet" href="<% asset('static/css/responsive.css') %>" />
    </head>

 <body data-spy="scroll" data-target=".navbar-fixed-top" ng-app="frontModule">
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=127269751011528";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

@include('layouts.front._navbar')

    <!-- Slider start -->

    @yield('content')


@include('layouts.front._footer')

<!-- Footer Area End -->

<!-- Back To Top Button -->
    <div id="back-top">
        <a href="#slider_part" class="scroll" data-scroll>
            <button class="btn btn-primary" title="Back to Top"><i class="fa fa-angle-up"></i></button>
        </a>
    </div>
    <!-- End Back To Top Button -->



<!-- Javascript Files
    ================================================== -->
    <!-- initialize jQuery Library -->

		<!-- initialize jQuery Library -->
        <!-- Main jquery -->
		    <script type="text/javascript" src="<% asset('static/js/jquery.js') %>"></script>
        <!-- Bootstrap jQuery -->
         <script src="<% asset('static/js/bootstrap.min.js') %>"></script>
        <!-- Owl Carousel -->
        <script src="<% asset('static/js/owl.carousel.min.js') %>"></script>
        <!-- Isotope -->
        <script src="<% asset('static/js/jquery.isotope.js') %>"></script>
        <!-- Pretty Photo -->
		    <script type="text/javascript" src="<% asset('static/js/jquery.prettyPhoto.js') %>"></script>
        <!-- SmoothScroll -->
        <script type="text/javascript" src="<% asset('static/js/smooth-scroll.js') %>"></script>
        <!-- Image Fancybox -->
        <script type="text/javascript" src="<% asset('static/js/jquery.fancybox.pack.js?v=2.1.5') %>"></script>
        <!-- Counter  -->
        <script type="text/javascript" src="<% asset('static/js/jquery.counterup.min.js') %>"></script>
        <!-- waypoints -->
        <script type="text/javascript" src="<% asset('static/js/waypoints.min.js') %>"></script>
        <!-- Bx slider -->
        <script type="text/javascript" src="<% asset('static/js/jquery.bxslider.min.js') %>"></script>
        <!-- Scroll to top -->
        <script type="text/javascript" src="<% asset('static/js/jquery.scrollTo.js') %>"></script>
        <!-- Easing js -->
        <script type="text/javascript" src="<% asset('static/js/jquery.easing.1.3.js') %>"></script>
   		 <!-- PrettyPhoto -->
        <script src="<% asset('static/js/jquery.singlePageNav.js') %>"></script>
      	<!-- Wow Animation -->
        <script type="js/javascript" src="<% asset("/static/js/wow.min.js") %>"></script>
        <!-- Google Map  Source -->

		<!-- Custom js -->

		<script src="<% asset('static/vendors/bootstrap/bootstrap.min.js') %>"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-animate.js"></script>
        <script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-1.3.3.js"></script>

		<script>var $ = jQuery;</script>
         <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-aria.min.js"></script>
         <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWxsxhsJe955T2Sgp1DqtvXW76AnBRe8A&libraries=places"></script>
         <script src="<% asset('static/vendors/google-autocomplete/autocomplete.min.js') %>"></script>
         <script type="text/javascript" src="<% asset('static/vendors/slick/slick.min.js') %>"></script>
         <script type="text/javascript" src="<% asset('static/vendors/slick/angular-slick.js') %>"></script>


		<script type="text/javascript" src="<% asset('static/vendors/angularui/ui-bootstrap-tpls-1.3.3.js') %>"></script>

         <!-- Angular Material Javascript now available via Google CDN; version 1.0.7 used here -->
         <script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.0.7/angular-material.min.js"></script>
         <script src="<% asset('static/js/custom.js') %>"></script>
         <script src="<% asset('static/js/services/frontServices.js') %>"></script>
         <script src="<% asset('static/js/front/script.js?v=1.0.0') %>"></script>


		@yield('pageJS')
 
    </body>
</html>
