<?php
$menuSelected = "reports";
$angularModule = 'reportModule';
?>
@extends('......layouts.admin.master')



@section('title')
Dashboard
@endsection

@section('content')

	<script>

	</script>
	<style>
        .grid {
          width: 100%;
        }
    </style>
    <div ng-controller="ActiveProjectController" id="lead-view">
		<div class="page-title">
	        <span class="title">Active Projects</span>
	    </div>
		<div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <div id="grid1" ui-grid="gridOptions" ui-grid-exporter class="grid"></div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('pageLevelJS')


<script type="text/javascript" src="<% asset('static/vendors/lodash/lodash.underscore.js')%>"></script>
<script type="text/javascript" src="<% asset('static/vendors/angular-simple-logger/angular-simple-logger.min.js')%>"></script>
<script type="text/javascript" src="<% asset('static/vendors/angular-google-maps/angular-google-maps.js')%>"></script>

<script type="text/javascript" src="<% asset('static/js/services/userServices.js')%>"></script>

<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>

<script type="text/javascript" src="<% asset('static/js/services/reportServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/reportscript.js')%>"></script>


@endsection