@extends('layouts.admin.master')
<?php
$menuSelected = "complaints";
$angularModule = 'adminModule';

?>
@section('title')
    New Complaint
@endsection

@section('content')
	<div id="new-lead-view" ng-controller="adminComplaintsCtrl">

		<div class="page-title">
            <span class="title">New Complaint</span>
            
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Complaint details</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form novalidate>
                            <div class="sub-title required">Complaint Type</div>
                            <div>
                                <ui-select ng-model="complaintData.userType" theme="bootstrap" on-select="changeUserType($item)">
                                    <ui-select-match placeholder="Select User Type">{{$select.selected.label}}</ui-select-match>
                                    <ui-select-choices repeat="item.id as item in userTypes | filter: $select.search">
                                        <div ng-bind-html="item.label | highlight: $select.search"></div>
                                    </ui-select-choices>
                                </ui-select>
                            </div>

                            <div class="sub-title required">Complaint Category</div>
                            <div>
                                <ui-select ng-model="complaintData.category" theme="bootstrap" ng-required="required" on-select="getComplaintSubCategories($item.id)">
                                    <ui-select-match placeholder="Select Category">{{$select.selected.name}}</ui-select-match>
                                    <ui-select-choices repeat="item.id as item in complaintCategories">
                                        {{item.name}}
                                    </ui-select-choices>
                                </ui-select>
                            </div>

                            <div style="margin-top: 15px;">
                                <ui-select ng-show="complaintSubCategories.length" ng-model="complaintData.complaintSubCategories" theme="bootstrap" ng-required="required" on-select="getComplaintSubChildCategories($item.id)">
                                    <ui-select-match placeholder="Select Sub-Category">{{$select.selected.name}}</ui-select-match>
                                    <ui-select-choices repeat="item.id as item in complaintSubCategories">
                                        {{item.name}}
                                    </ui-select-choices>
                                </ui-select>
                            </div>

                            <div style="margin-top: 15px;">
                                <ui-select ng-show="complaintSubChildCategories.length" ng-model="complaintData.complaintSubChildCategories" theme="bootstrap" ng-required="required">
                                    <ui-select-match placeholder="Select child-Category">{{$select.selected.name}}</ui-select-match>
                                    <ui-select-choices repeat="item.id as item in complaintSubChildCategories">
                                        {{item.name}}
                                    </ui-select-choices>
                                </ui-select>
                            </div>


                            <!-- <div>
                                <md-input-container>
                                    <md-select ng-model="complaintData.category" class="col-md-12" ng-required="required">

                                        <md-optgroup ng-repeat="item in complaintCategories" label="{{item.name}}">
                                            <md-optgroup flex-offset="5" ng-if="subItem.sub_categories_childs.length" ng-repeat="subItem in item.sub_categories" label="{{subItem.name}}" style="margin-left:30px;">


                                            <md-option ng-repeat="subItemChild in subItem.sub_categories_childs" value="{{subItemChild.id}}">{{ subItemChild.name }}</md-option>

                                            </md-optgroup>

                                            <md-option ng-if="!subItem.sub_categories_childs.length" ng-repeat="subItem in item.sub_categories" value="{{subItem.id}}">{{ subItem.name }}</md-option>

                                        </md-optgroup>

                                    </md-select>
                                </md-input-container>
                            </div> -->

                            <div class="sub-title required">User (Complaint By)</div>
                            <div>
                                <ui-select ng-model="complaintData.userId" theme="bootstrap" on-select="getUserLeads($item.id)">
                                    <ui-select-match placeholder="Select User">{{$select.selected.name}} - {{$select.selected.phone}} - {{$select.selected.email}}</ui-select-match>
                                    <ui-select-choices refresh="searchuser($select.search)" refresh-delay="0" minimum-input-length="3" repeat="item.id as item in usersList | filter: $select.search">
                                        {{item.name}} - {{item.phone}} - {{item.email}} / {{item.id}}
                                    </ui-select-choices>
                                </ui-select>
                            </div>

                            <div class="sub-title required">Lead</div>
                            <div>
                                <ui-select ng-model="complaintData.lead_id" theme="bootstrap" on-select="getCurrentVendor($item.lead_id)">
                                    <ui-select-match placeholder="Select Lead">{{$select.selected.lead_id}} - {{$select.selected.created_at}}</ui-select-match>
                                    <ui-select-choices repeat="item.lead_id as item in leadsList | filter: $select.search">
                                        {{item.lead_id}} - {{item.created_at}}
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div ng-show="vendorInfo">CG: {{vendorInfo.name}}</div>
                            <div ng-show="vendorInfoFalse">No Vendor Found for this lead.</div>
                            
                            <div class="sub-title">Details</div>
                            <div>
                                <textarea class="form-control" ng-model="complaintData.details" placeholder="Details (optional)"></textarea>
                            </div>

                            <div class="spacer-5"></div>

                            <div class="pull-left">
                                <button class="btn btn-success" ng-click="addComplaint()" ng-disabled="!complaintData.category || !complaintData.userId || !complaintData.lead_id">Save</button>
                            </div>
                        </form>

                        <div class="spacer-5"></div>

                    </div>
                </div>
                <div class="spacer-5"></div>

            </div>
        </div>
	</div>



@endsection

@section('pageLevelJS')



<script type="text/javascript" src="<% asset('static/js/services/userServices.js')%>"></script>


<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>

<script type="text/javascript" src="<% asset('static/js/services/vendorServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>
@endsection