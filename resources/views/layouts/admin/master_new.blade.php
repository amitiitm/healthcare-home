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

        <link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        
        <!--<link href="<% asset('static/vendors/google-autocomplete/autocomplete.min.css') %>" rel="stylesheet">-->

        <link href="<% asset('static/vendors/datepicker/datepicker3.css') %>" rel="stylesheet">
        <link href="<% asset('static/vendors/bootstrap-timepicker/css/timepicker.css') %>" rel="stylesheet">


        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="<% asset('static/vendors/md-data-tables/md-data-table.css') %>" rel="stylesheet">


        <!-- CSS App -->
        <link rel="stylesheet" href="<% asset('static/css/pramati-font.css') %>" />
        <link rel="stylesheet" type="text/css" href="<%asset('static/css/admin.css')%>">
        <link rel="stylesheet" type="text/css" href="<%asset('static/css/themes/flat-light.css')%>" />
        <link rel="stylesheet" type="text/css" href="<%asset('static/css/material.css')%>">
        <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyBWxsxhsJe955T2Sgp1DqtvXW76AnBRe8A&libraries=places"></script>

        <!-- Javascript Libs -->

        <script type="text/javascript" src="<% asset('static/vendors/jquery/jquery.min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/bootstrap/bootstrap.min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/Chartjs/Chart.min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/bootstrap-switch/js/bootstrap-switch.min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/matchHeight/jquery.matchHeight-min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/datatables/js/jquery.dataTables.min.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/vendors/datatables/js/dataTables.bootstrap.min.js')%>"></script>
        <!--<script type="text/javascript" src="<% asset('static/vendors/md-data-tables/md-data-table.min.js')%>"></script>-->
        <script type="text/javascript" src="<% asset('static/vendors/bootstrap/bootstrap.min.js')%>"></script>

        <!--<script type="text/javascript" src="<% asset('static/vendors/ui-select/select.min.js')%>"></script>-->
        <!--<script src="<% asset('static/vendors/google-autocomplete/autocomplete.min.js') %>"></script>-->

    </head>

    <body class="flat-blue">

    <md-content>
        <div class="app-container <?php
        if (isset($sidebarExpanded) && ($sidebarExpanded) == true) {
            echo 'expanded';
        }
        ?>">
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

    <?php
    if (env('APP_ENV') == "production") {
        ?>



        <?php
    }
    ?>

    <script>
                $(document).ready(function () {
            $('#data-table-grid').DataTable();
        });
    </script>
    @yield('pageLevelJS')

</body>

</html>
