<?php
$menuSelected = "employees";
$angularModule = 'employeeModule';

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
    <div ng-controller="EmployeeTrackingController" id="lead-view">
		<div class="page-title">
	        <span class="title">Pramati Care Employee Tracking</span>
	    </div>
		<div class="row">
            <div class="col-md-3">
                <input type="text" ng-model="searchEmployee" class="form-control" placeholder="Search Employee (enter min 3 characters)" ng-keyup="fetchUser()"/>
                <div style="margin-top: 5px;"></div>
				<div style="max-height: 600px; overflow-y: scroll">
					<ul class="list-group">
                        <li  ng-click="showLocation(employee)" class="list-group-item" style="cursor: pointer" ng-repeat="employee in employeeList | filter: { name: searchEmployee }">
                            <span ng-bind="employee.name"></span>
                            <span class="pull-right" ng-show="employee.locations.length>0">
                                <i class="fa fa-circle color-online"></i>
                            </span>
                        </li>
                    </ul>
				</div>
            </div>
            <div class="col-md-9">
                <div>
                    <h4>
                        <span ng-bind="selectedEmployee.name"></span>
                        <small ng-show="selectedEmployee.name">Last Location Captured at: {{selectedEmployee.locations[selectedEmployee.locations.length-1].timeStamp|carbontime}}</small>
                    </h4>

                </div>

                <ui-gmap-google-map center="map.center" zoom="map.zoom" draggable="true" events="map.events">
                    <ui-gmap-marker ng-repeat="m in map.markers" coords="m.coords" idkey="m.id" options="m.options">
                        <ui-gmap-window models="m" coords="m.coords" show="m.show" isIconVisibleOnClick="true">
                            <div>
                                <div><strong>{{m.employeeData.name}} ({{m.employeeData.mobile}})</strong></div>
                                <div><span>Last Location Captured at: {{m.employeeData.locations[m.employeeData.locations.length-1].timeStamp|carbontime}}</div>
                                <div><strong>{{m.employeeData.formattedAddress}}</strong></div>
                            </div>
                        </ui-gmap-window>
                    </ui-gmap-marker>
                </ui-gmap-google-map>
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

<script type="text/javascript" src="<% asset('static/js/services/employeeServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/employeescript.js')%>"></script>



@endsection