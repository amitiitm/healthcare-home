<!DOCTYPE html>
<html>
<head>
    <title>Pramaticare - @yield('title')</title>
    <link href="<% asset('icon.png') %>" rel="icon">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<%asset('static/css/bootstrap.min.css')%>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<%asset('static/vendors/animate.css/animate.min.css')%>">
    <link rel="stylesheet" type="text/css" href="<%asset('static/vendors/bootstrap-switch/css/bootstrap3/bootstrap-switch.css')%>">
    <link rel="stylesheet" type="text/css" href="<%asset('static/vendors/checkbox3/dist/checkbox3.min.css')%>">
    <link rel="stylesheet" type="text/css" href="<%asset('static/vendors/datatables/css/jquery.dataTables.min.css')%>">
    <link rel="stylesheet" type="text/css" href="<%asset('static/vendors/datatables/css/dataTables.bootstrap.min.css')%>">
    <link rel="stylesheet" type="text/css" href="<%asset('static/vendors/ui-select/select.css')%>">


    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.8.5/css/selectize.default.css">
    <link rel="stylesheet" type="text/css" href="<%asset('static/vendors/angular-ui-grid/ui-grid.min.css')%>">
    <link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link href="<% asset('static/vendors/google-autocomplete/autocomplete.min.css') %>" rel="stylesheet">

	<link href="<% asset('static/vendors/datepicker/datepicker3.css') %>" rel="stylesheet">
	<link href="<% asset('static/vendors/bootstrap-timepicker/css/timepicker.css') %>" rel="stylesheet">

	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/0.11.2/angular-material.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="<% asset('static/vendors/md-data-tables/md-data-table.css') %>" rel="stylesheet">
	<link href="<% asset('static/vendors/angular-ui-grid/ui-grid.min.css') %>" rel="stylesheet">

    <!-- CSS App -->
    <link rel="stylesheet" href="<% asset('static/css/pramati-font.css') %>" />
    <link rel="stylesheet" type="text/css" href="<%asset('static/css/admin.css')%>">
    <link rel="stylesheet" type="text/css" href="<%asset('static/css/themes/flat-light.css')%>" />
    <link rel="stylesheet" type="text/css" href="<%asset('static/css/material.css')%>">
    <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyBWxsxhsJe955T2Sgp1DqtvXW76AnBRe8A&libraries=places"></script>

</head>

<body ng-cloak class="flat-blue" ng-app="<?php if(isset($angularModule)){ echo $angularModule; } ?>">
	<div id="loaderContainer" ng-show="loadingMask">
		<div class="loader-mask"></div>
		<table width="100%" height="100%">
			<tr>
			<td align="center">
				<img src="<% url('static/images/gear.gif')%>" />
				<p>Please wait...</p>
			</td>
			</tr>
		</table>
	</div>
	<md-content>
        <div class="app-container <?php  if(isset($sidebarExpanded) && ($sidebarExpanded)==true){ echo 'expanded'; }?>">
        <div class="row content-container">
            @include('layouts.admin._navbar')
            @include('layouts.admin._sidemenu')

            <!-- Main Content -->
            <div class="container-fluid">
                <div class="side-body ">
                    @yield('content')
                </div>
            </div>
        </div>

	</div>
	</md-content>
        <!-- Javascript Libs -->

        <script type="text/javascript" src="<% asset('static/vendors/jquery/jquery.min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/bootstrap/bootstrap.min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/Chartjs/Chart.min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/bootstrap-switch/js/bootstrap-switch.min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/matchHeight/jquery.matchHeight-min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/datatables/js/jquery.dataTables.min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/datatables/js/dataTables.bootstrap.min.js')%>"></script>



        <!-- Javascript -->
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular-animate.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular-aria.min.js"></script>
        <!-- Angular Material Javascript now available via Google CDN; version 1.0.7 used here -->
        <script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.0.7/angular-material.min.js"></script>
                <script type="text/javascript" src="<% asset('static/vendors/md-data-tables/md-data-table.min.js')%>"></script>
                <script type="text/javascript" src="<% asset('static/vendors/angular-ui-grid/ui-grid.min.js')%>"></script>
                <script type="text/javascript" src="<% asset('static/vendors/angular-ui-grid/csv.js')%>"></script>


                <script type="text/javascript" src="<% asset('static/vendors/angular-ui-grid/pdfmake.js')%>"></script>
                <script type="text/javascript" src="<% asset('static/vendors/bootstrap/bootstrap.min.js')%>"></script>
                <script type="text/javascript" src="<% asset('static/vendors/angularui/ui-bootstrap-tpls-1.1.0.min.js')%>"></script>
                <script type="text/javascript" src="<% asset('static/vendors/ngtimeago/ngtimeago.js')%>"></script>
                	    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular-sanitize.js"></script>
                        <script type="text/javascript" src="<% asset('static/vendors/ui-select/select.min.js')%>"></script>
                        <script src="<% asset('static/vendors/google-autocomplete/autocomplete.min.js') %>"></script>
                        <script src="<% asset('static/vendors/ng-file-upload-master/dist/ng-file-upload-all.min.js') %>"></script>
                        <script src="<% asset('static/js/directives/imageupload.js') %>"></script>

        <script type="text/javascript" src="<% asset('static/js/admin/app.js')%>"></script>


		<script type="text/javascript">
        var baseUrl = "<?php echo url(); ?>";
        var userId = "<?php //echo Auth::user()->id; ?>";

        function isFunction(functionToCheck) {
            var getType = {};
            return !!(functionToCheck && getType.toString.call(functionToCheck) === '[object Function]');
        }
        function loadScript(url, callback) {
            url = baseUrl+url;
            var script = document.createElement("script");
            script.type = "text/javascript";
            if (script.readyState) {
                script.onreadystatechange = function () {
                    if (script.readyState == "loaded" || script.readyState == "complete") {
                        script.onreadystatechange = null;
                        if (callback && isFunction(callback)) {
                            callback();
                        }
                    }
                };
            } else {
                script.onload = function () {
                    if (callback && isFunction(callback)) {
                        callback();
                    }
                };
            }
            script.src = url;
            document.getElementsByTagName("head")[0].appendChild(script);
        };
        <?php
        if(env('APP_ENV')=="production"){
            ?>



            <?php
        }
        ?>
    </script>

	@yield('pageLevelJS')

</body>

</html>
