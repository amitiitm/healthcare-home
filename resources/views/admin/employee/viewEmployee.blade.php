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
        var employeeId = '<?php echo $employeeId; ?>';
	</script>
	<style>
        .grid {
          width: 100%;
        }
    </style>
    <div ng-controller="EmployeeViewController" id="lead-view">
		<div class="page-title ">
	        <span class="title">Employee Details</span>
	    </div>
        <div class="card profile">
            <div class="card-header">
                <div class="card-title">
                    <span ng-bind="userData.name" class="title"></span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
	                    <div class="col-md-4  ">
	                        <div class="text-center"> <img  class="img-responsive width-200" style="margin: auto" ng-src="{{userData.userImage}}"> </div>
	                    </div>
	                    <div class="col-md-5 description ">
	                        <div class="row">
	                            <div class="col-md-4"><label>Name</label></div>
	                            <div class="col-md-8"><p>{{userData.name}}</p></div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-4"><label>Email</label></div>
	                            <div class="col-md-8"><p>{{userData.email}}</p></div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-4"><label>Phone</label></div>
	                            <div class="col-md-8"><p>{{userData.phone}}</p></div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-4"><label>Address</label></div>
	                            <div class="col-md-8"><p>{{userData.address}}</p></div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-4"><label>Departments</label></div>
	                            <div class="col-md-8"><ul class="list-unstyled"></ul></div>
	                        </div>

	                    </div>
	                    <div class="col-md-3 ">
	                        <div >
	                        </div>
	                        <a class="btn btn-default" href="<% url('admin/employee/edit/'.$employeeId) %>" role="button" >Edit Details</a>
	                    </div>
	            </div>
            </div>
        </div>
		<div class="row" style="visibility: hidden">
            <div class="col-md-12">
                <div class="col-md-4">left</div>
                <div class="col-md-4">center</div>
                <div class="col-md-4">right</div>
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