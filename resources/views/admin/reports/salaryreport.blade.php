<?php
$menuSelected = "salaryreport";
$angularModule = 'reportModule';
?>
@extends('......layouts.admin.master')



@section('title')
Salaray Reports
@endsection

@section('content')

<style>
.grid {
	width: 100%;
}
.ui-grid-render-container-body {
	position: absolute;
	margin-left: 31px;
}

.ui-grid-grid-footer {
	position: absolute;
	bottom: 25px;
}
</style>

<div ng-controller="SalaryController">
	<div class="page-title">
		<span class="title">Caregiver Salary Report</span>
	</div>

	<div class="row">
        <div class="col-md-3">
            <input type="date" class="form-control" ng-model="reportDateFrom">
        </div>
        <div class="col-md-1 text-center">
            to
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control" ng-model="reportDateTo">
        </div>
        <!-- <div class="col-md-2">
            <select ng-model="selectedShowMode" ng-options="x for x in showModes" class="form-control">
			</select>
        </div> -->
        <div class="col-md-3">
            <button class="btn btn-success" ng-click="getSalaryReport()">Show</button>
        </div>
	</div>
	<div class="margin-top-5"></div>
	
	<div class="row">
		<div class="col-md-12" id="gridDiv">

			<div ui-grid="gridOptions" ui-grid-pinning ui-grid-expandable class="grid" style="height:450px;"></div>
			<!-- <div ui-grid="gridOptions" ui-grid-pinning ui-grid-expandable class="grid" style="height:450px;" ng-show="ngShowMode=='Customer'"></div> -->

		</div>
	</div>
</div>

<script type="text/ng-template" id="expandableRowTemplate.html">
    <div ui-grid="row.entity.subGridOptions" style="height:150px;"></div>
</script>

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