<?php
$menuSelected = "employees";
$angularModule = 'vendorModule';
?>
@extends('......layouts.admin.master')

@section('title')
Vendors
@endsection

@section('content')

	<script>

	</script>
	<style>
        .grid {
          width: 100%;
        }
    </style>
    <div ng-controller="VendorListController" id="lead-view">
		<div class="page-title">
	        <span class="title">Care Givers</span>

            <button class="btn btn-danger pull-right" ng-click="deleteVendors()">Delete Vendors</button>
	    </div>
		<div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <div id="grid1" ui-grid="gridOptions"  ui-grid-selection  ui-grid-exporter ui-grid-pagination class="grid"></div>
                </div>
            </div>
        </div>

    </div>


    <script type="text/ng-template" id="approveLeadModalTemplate.html">
        <div class="modal-header">
            <h3 class="modal-title">Approve Lead</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="sub-title">Enter some more information about lead appoval</div>
                    <div>
                        <textarea class="form-control" ng-model="statusChangeObject.comment" rows="3"></textarea>
                    </div>
                    <div class="sub-title">Do you want to change employee assignment?</div>

                    <div>
                        <input type="text" ng-model="statusChangeObject.user" placeholder="Employee Name" uib-typeahead="user as user.name for user in employeeList | filter:{name:$viewValue}" class="form-control" typeahead-show-hint="true" typeahead-min-length="0">
                    </div>
                </div>
            </div>
            <div class="col-md-"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-click="approveLead()">Update</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>

    <script type="text/ng-template" id="deleteVendorsModalTemplate.html">
            <div class="modal-header">
                <h3 class="modal-title">Delete Selected Vendors </h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="sub-title">Do you realy want to delete these Caregivers?</div>

                    </div>
                </div>
                <div class="col-md-"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success"  type="button" ng-click="deleteVendors()">Yes Delete</button>
                <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
            </div>
        </script>


    <div class="new-user-button">

        <a href="<% url('operation/vendor/add')%>">
        <md-button aria-label="menu" class="md-fab md-primary">
            <i class="fa fa-plus"></i>
        </md-button>
        </a>
    </div>

@endsection

@section('pageLevelJS')


<script type="text/javascript" src="<% asset('static/js/services/userServices.js')%>"></script>


<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>

<script type="text/javascript" src="<% asset('static/js/services/vendorServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/vendorscript.js')%>"></script>



@endsection