<?php
$menuSelected = "leads";
$angularModule = 'adminModule';

?>
@extends('layouts.admin.master')

@section('title')
Dashboard
@endsection

@section('content')
	<script>
		var leadId = '<% $model['leadId'] %>';
	</script>
	<style>
        .grid {
          width: 100%;
        }
    </style>
    <div ng-controller="operationLeadCtrl" id="lead-view">
        <div class="page-title">
            <div class="title">
                <span>Lead: <% $model['lead']->customer_name%> <% $model['lead']->customer_last_name%></span>
                <small ng-show="lead.patient.name">({{lead.patient.name}})</small>
                <a ng-show="lead.slackChannelId!='' && lead.slackChannelName!=''" class="btn btn-success btn-xs" href="{{getSlackChannelLink(lead.slackChannelName)}}" target="_blank"><i class="fa fa-slack"></i> Slack</a>
                <a ng-show="lead.slackChannelId=='' || lead.slackChannelName==''" class="btn btn-success btn-xs" href="{{getSlackChannelCreateUrl(lead.id)}}"><i class="fa fa-slack"></i> Generate Slack</a>
				<span class="app-installed-icon" ng-class="{'customer-app-installed': lead.appInstalled}">
					<i class="fa fa-android"></i>
				</span>

                <div class="pull-right">
                        <button class="btn btn-sm btn-default" ng-click="deleteLead()" ng-show="!isLeadValidated()">Delete Lead</button>
                        <a href="<% url("/operation/lead/edit/".$model['lead']['id']) %>" class="btn btn-sm btn-default">Edit Lead</a>
                    <span class="badge badge-success badge-large bg-success" ng-bind="lead.service.name"></span>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-8">
				<div class="card patient-info">
	                <div class="card-header">
	                    <div class="card-title">
	                        <div class="title">Patient Info</div>
	                    </div>
	                    <div class="pull-right card-action">
	                        <div class="btn-group ng-hide" role="group" aria-label="...">
	                            <button type="button" class="btn btn-link"><i class="fa fa-edit"></i></button>
	                        </div>
	                    </div>
	                </div>
	                <div class="card-body">
	                    <div class="row">
	                        <div class="col-md-12">
	                            <div class="row">
	                                <div class="col-md-6 col-sm-6">
	                                    Name of patient
	                                </div>
	                                <div class="col-md-6 col-sm-6">
	                                    <span ng-bind="lead.patient.name"></span>
	                                </div>
	                            </div>
	                            <div class="row">
	                                <div class="col-md-6 col-sm-6">
	                                    Gender
	                                </div>
	                                <div class="col-md-6 col-sm-6">
	                                    <span ng-bind="lead.patient.gender.label"></span>
	                                </div>
	                            </div>
	                            <div class="row">
	                                <div class="col-md-6 col-sm-6">
	                                    Age
	                                </div>
	                                <div class="col-md-6 col-sm-6">
	                                    <span ng-bind="lead.patient.age" ng-show="lead.patient.age>0"></span>
	                                </div>
	                            </div>
	                            <div class="row">
	                                <div class="col-md-6 col-sm-6">
	                                    Weight
	                                </div>
	                                <div class="col-md-6 col-sm-6">
	                                    <span ng-bind="lead.patient.weight"  ng-show="lead.patient.weight>0"></span>
	                                </div>
	                            </div>
	                            <div class="row">
	                                <div class="col-md-6 col-sm-6">
	                                    Ailment
	                                </div>
	                                <div class="col-md-6 col-sm-6">
	                                    <span ng-repeat="ailment in lead.patient.ailments" class="badge ailment bg-warning" ng-bind="ailment.name"></span>
	                                </div>
	                            </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Other Ailment
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.otherAilment" ng-show="lead.patient.otherAilment"></span>
                                    </div>
                                </div>

                                <div class="row" ng-show="lead.patient.shiftRequired.label">
                                    <div class="col-md-6 col-sm-6">
                                        Shift Required
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.shiftRequired.label"></span>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Task Required
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-repeat="task in lead.patient.tasks" class="badge task bg-warning" ng-bind="task.label"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Other Task Required
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.taskOther"></span>
                                    </div>
                                </div>
                                <div ng-show="showMoreEmployeeDetail">
	                                <div class="row">
	                                    <div class="col-md-6 col-sm-6">
	                                        On Equipment Support
	                                    </div>
	                                    <div class="col-md-6 col-sm-6">
	                                        <span ng-show="lead.patient.isOnEquipmentSupport">Yes</span>
	                                        <span ng-show="!lead.patient.isOnEquipmentSupport">No</span>
	                                    </div>
	                                </div>
	                                <div class="row" ng-show="lead.patient.isOnEquipmentSupport">
	                                    <div class="col-md-6 col-sm-6">
	                                        Equipment
	                                    </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-repeat="equipment in lead.patient.equipments" class="badge ailment bg-warning" ng-bind="equipment.name"></span>
                                        </div>
	                                </div>
                                    <div class="row" ng-show="lead.patient.mobility.label">
                                        <div class="col-md-6 col-sm-6">
                                            Mobility
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-bind="lead.patient.mobility.label"></span>
                                        </div>
                                    </div>
                                    <div class="row" ng-show="lead.patient.illness">
                                        <div class="col-md-6 col-sm-6">
                                            Illness
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-bind="lead.patient.illness"></span>
                                        </div>
                                    </div>
                                    <div class="row" ng-show="lead.patient.physicalCondition">
                                        <div class="col-md-6 col-sm-6">
                                            Physical Condition
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-bind="lead.patient.physicalCondition"></span>
                                        </div>
                                    </div>
                                    <div class="row" ng-show="lead.patient.morningWakeUpTime">
                                        <div class="col-md-6 col-sm-6">
                                            Morning Wakeup Time
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="lead.patient.morningWakeUpTime!='00:00:00'" ng-bind="lead.patient.morningWakeUpTime"></span>
                                            <span ng-show="lead.patient.morningWakeUpTime=='00:00:00'">Not Available</span>
                                        </div>
                                    </div>
                                    <div class="row" ng-show="lead.patient.walkTiming">
                                        <div class="col-md-6 col-sm-6">
                                            Walking Time
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="lead.patient.walkTiming!='00:00:00'" ng-bind="lead.patient.walkTiming"></span>
                                            <span ng-show="lead.patient.walkTiming=='00:00:00'">Not Available</span>
                                        </div>
                                    </div>
                                    <div class="row ng-hide" ng-show="lead.patient.walkLocation">
                                        <div class="col-md-6 col-sm-6">
                                            Walking Location
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-bind="lead.patient.walkLocation"></span>
                                        </div>
                                    </div>
                                    <div class="row" ng-show="lead.patient.breakfastTime">
                                        <div class="col-md-6 col-sm-6">
                                            Breakfast Time
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="lead.patient.breakfastTime!='00:00:00'" ng-bind="lead.patient.breakfastTime"></span>
                                            <span ng-show="lead.patient.breakfastTime=='00:00:00'">Not Available</span>
                                        </div>
                                    </div>
                                    <div class="row" ng-show="lead.patient.lunchTime">
                                        <div class="col-md-6 col-sm-6">
                                            Lunch Time
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                             <span ng-show="lead.patient.lunchTime!='00:00:00'" ng-bind="lead.patient.lunchTime"></span>
                                             <span ng-show="lead.patient.lunchTime=='00:00:00'">Not Available</span>
                                        </div>
                                    </div>
                                    <div class="row" ng-show="lead.patient.dinnerTime">
                                        <div class="col-md-6 col-sm-6">
                                            Dinner Time
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                             <span ng-show="lead.patient.dinnerTime!='00:00:00'" ng-bind="lead.patient.dinnerTime"></span>
                                             <span ng-show="lead.patient.dinnerTime=='00:00:00'">Not Available</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Religion Preference
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.religionPreference">No</span>
                                            <span ng-show="lead.patient.religionPreference" ng-bind="lead.patient.religionPreferred.label"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Age Preference
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.agePreferece">No</span>
                                            <span ng-show="lead.patient.agePreferece" ng-bind="lead.patient.agePreferred.label"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Food Preference
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.foodPreference">No</span>
                                            <span ng-show="lead.patient.foodPreference" ng-bind="lead.patient.foodPreferred.label"></span>
                                        </div>
                                    </div>
                                    <div class="row ng-hide">
                                        <div class="col-md-6 col-sm-6">
                                            Gender Preference
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.genderPreference">No</span>
                                            <span ng-show="lead.patient.genderPreference">
                                                <span ng-show="lead.patient.genderPreferred=='F'">Female</span>
                                                <span ng-show="lead.patient.genderPreferred=='M'">Male</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row ng-hide">
                                        <div class="col-md-6 col-sm-6">
                                            Language Preference
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.languagePreference">No</span>
                                            <span ng-show="lead.patient.languagePreference" ng-bind="lead.patient.languagePreferred.label"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Doctor
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.doctorName">None</span>
                                            <span ng-show="lead.patient.doctorName" ng-bind="lead.patient.doctorName"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Hospital
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.hospitalName">None</span>
                                            <span ng-show="lead.patient.hospitalName" ng-bind="lead.patient.hospitalName"></span>
                                        </div>
                                    </div>
                                </div>
	                            <div class="text-center">
	                                <button class="btn btn-link no-margin" ng-click="toggleEmployeeDetail()">
	                                    <span ng-show="!showMoreEmployeeDetail">Show More</span>
	                                    <span ng-show="showMoreEmployeeDetail">Show Less</span>
	                                </button>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="lead-action-item">
	                <button class="btn btn-success btn-sm" ng-click="updateLeadStatus()">Update Status</button>
	                <button class="btn btn-success btn-sm" ng-click="approveLead()" ng-show="!isLeadApproved() && !isLeadValidated()">Approve Lead</button>
	                <a href="<% url('operation/lead/validate/'.$model['leadId']) %>" ng-show="!isLeadValidated()" class="btn btn-success btn-sm">Validate Sales</a>
	                <a href="<% url('operation/lead/validate/'.$model['leadId']) %>" ng-show="false && isLeadValidated()" class="btn btn-success btn-sm">Re-validate Sales</a>
	                <a href="<% url('operation/lead/careplan/'.$model['leadId']) %>" ng-show="isLeadValidated()" class="btn btn-success btn-sm">Care Plan</a>
	                <button class="btn btn-success btn-sm" ng-click="startService()" ng-show="isLeadValidated() && readyToStart()">Start Service</button>
	                <!--<button class="btn btn-success btn-sm" ng-click="startService()" ng-show="true">Start Service</button>-->
                        <button class="btn btn-success btn-sm" ng-click="markAttendance()" ng-show="isLeadStarted() && !isLeadClosed()">Mark Attendance</button>
	                <button class="btn btn-success btn-sm" ng-click="closeService()" ng-show="!isLeadClosed()">Close Service</button>
	                <button class="btn btn-success btn-sm" ng-click="startService()" ng-show="isLeadOnHold()">Un-hold Service</button>
	                <button class="btn btn-success btn-sm" ng-click="notifyCustomer()" >Notify Customer</button>
	            </div>
				<div class="lead-logs">
					<div ng-repeat="logItem in lead.logs  ">
						<div ng-if="logItem.taskType=='comment'">
							<div class="lead-log-item">
                                <div class="lead-log-user">
                                    <img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <a href="#"><span ng-bind="logItem.taskUser.name"></span></a>
                                        <span> commented </span>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content comment">
                                        <span ng-bind="logItem.data.comment"></span>
                                    </div>
                                </div>
                            </div>
						</div>
                        <div ng-if="logItem.taskType=='complaint_log'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <a href="#"><span ng-bind="logItem.taskUser.name"></span></a>
                                        <span> updated complaint status. </span>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content comment">
                                        <span ng-bind="logItem.data.comment"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div ng-if="logItem.taskType=='employee_assignment'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <a href="#"><span ng-bind="logItem.taskUser.name"></span></a>
                                        <span> assigned employee </span>
                                        <a href="#"><span ng-bind="logItem.data.assignee.name"></span></a>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content">
                                    </div>
                                </div>
                            </div>
                        </div>
						<div ng-if="logItem.taskType=='qc_assignment'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <a href="#"><span ng-bind="logItem.taskUser.name"></span></a>
                                        <span> assigned QC </span>
                                        <a href="#"><span ng-bind="logItem.data.assignee.name"></span></a>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content">
                                    </div>
                                </div>
                            </div>
                        </div>
						<div ng-if="logItem.taskType=='field_assignment'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <a href="#"><span ng-bind="logItem.taskUser.name"></span></a>
                                        <span> assigned field executive </span>
                                        <a href="#"><span ng-bind="logItem.data.assignee.name"></span></a>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="logItem.taskType=='vendor_assignment'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <a href="#"><span ng-bind="logItem.taskUser.name"></span></a>
                                        <span> assigned care-giver </span>
                                        <a href="#"><span ng-bind="logItem.data.assignee.name"></span></a>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="logItem.taskType=='status_change'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <a href="#"><span ng-bind="logItem.taskUser.name"></span></a>
                                        <span> changed status </span>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content">
                                        <span class="badge bg-success" ng-bind="logItem.data.status.label"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="logItem.taskType=='vendor_attendance'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <a href="#"><span ng-bind="logItem.taskUser.name"></span></a>
                                        <span> marked caregiver attendance </span>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content">
                                        <span class="badge bg-success " ng-show="logItem.data.attendance==true">Present on {{ logItem.data.date | carbondate}}</span>
                                        <span class="badge bg-danger" ng-show="logItem.data.attendance==false">Absent</span>
                                        <br />
                                        <strong>Caregiver Present: </strong><span ng-show="logItem.data.attendance==true" ng-bind="logItem.data.vendor.name"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="logItem.taskType=='customer_status_change_request'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <!--<img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />-->
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <span>Customer </span>
                                        <span> requested to change status to </span>
                                        <strong ng-bind="logItem.data.status.label"></strong>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content">
                                        <div>
                                            <strong>Reason: </strong>
                                            <span ng-bind="logItem.data.reason.label"></span>
                                        </div>
                                        <div>
                                            <strong>CG Issue: </strong>
                                            <span ng-bind="logItem.data.issue.label"></span>
                                        </div>
                                        <span class="badge bg-pink">Customer Request</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="logItem.taskType=='customer_vendor_status'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <!--<img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />-->
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <span>Customer reported about caregiver </span>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content">
                                        <div>
                                            <strong>Caregiver Status: </strong>
                                            <span ng-bind="logItem.data.statusText"></span>
                                        </div>
                                        <div>
                                            <strong>Status at: </strong>
                                            <span ng-bind="logItem.data.statusDateString"></span>
                                        </div>
                                        <div>
                                            <strong>Comment: </strong>
                                            <span ng-bind="logItem.data.comment"></span>
                                        </div>
                                        <span class="badge bg-red">Customer Reporting</span>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="logItem.taskType=='customer_vendor_attendance'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <!--<img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />-->
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <span>Customer marked attendance </span>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content">

										<div>
											<strong>Attendance For: </strong>
											<span ng-bind="logItem.data.attendanceDateString"></span>
										</div>
                                        <div>
                                            <strong>Attendance: </strong>
                                            <span ng-show="logItem.data.isPresent">Present</span>
                                            <span ng-show="!logItem.data.isPresent">Absent</span>
                                        </div>
                                        <div ng-show="logItem.data.isPresent">
                                            <strong>Caregiver arrives on time: </strong>
		                                    <span ng-show="logItem.data.isOnTime">Yes</span>
		                                    <span ng-show="!logItem.data.isOnTime">No</span>
                                        </div>
                                        <div ng-show="logItem.data.isPresent">
                                            <strong>Caregiver well dressed: </strong>
		                                    <span ng-show="logItem.data.isWellDressed">Yes</span>
		                                    <span ng-show="!logItem.data.isWellDressed">No</span>
                                        </div>
                                        <span class="badge bg-orange">Caregiver attendance by customer</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="logItem.taskType=='customer_notification'">
                            <div class="lead-log-item">
                                <div class="lead-log-user">
                                    <img ng-src="{{logItem.taskUser.imageUrl}}?size=icon" class="img-responsive" />
                                </div>
                                <div class="lead-log-content">
                                    <div class="log-header">
                                        <a href="#"><span ng-bind="logItem.taskUser.name"></span></a>
                                        <span> notified customer about </span>
                                        <strong ng-bind="logItem.data.status.label"></strong>
                                        <small> {{logItem.dateTime.date | timeago}} </small>
                                    </div>
                                    <div class="log-content">
                                        <div>
                                            <strong>Header: </strong>
                                            <span ng-bind="logItem.data.header"></span>
                                        </div>
                                        <div>
                                            <strong>Content: </strong>
                                            <span ng-bind="logItem.data.content"></span>
                                        </div>
                                        <span class="badge bg-purple">Customer Notified</span>

                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
				<div class="comment-box">
					<div class="comment-user">
                        <img src="../static/images/user.png" class="img-responsive" />
                    </div>
                    <div class="comment-content">
                        <textarea class="form-control" rows="4" ng-model="userComment.comment"></textarea>
                        <button class="btn btn-sm btn-success pull-right" ng-disabled="userComment.comment==''" ng-click="addComment()">Comment</button>
                    </div>
				</div>
            </div>
            <div class="col-md-4">
                <div class="card" ng-show="lead.UserCreated.name">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">{{lead.UserCreated.name}}</div> created the lead
                        </div>
                    </div>
                </div>
                <div class="card" ng-show="!lead.UserCreated.name">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Created by: </div>Not available
                        </div>
                    </div>
                </div>
                <div class="lead-widget widget-assignee">
					<div class="header">
						<span>Employee Assigned</span>
						<div class="pull-right">
                            <button class="btn btn-link btn-xs ng-hide" ng-click="assignEmployee()">
                                <i class="fa fa-edit"></i>
                            </button>
                        </div>
					</div>

					<div class="widget-content">
						<div class="user-card" ng-show="lead.employeeAssigned.id">
	                        <div class="row">
	                            <div class="col-md-3">
	                                <div class="user-card-image">
	                                    <img ng-src="{{lead.employeeAssigned.imageUrl}}?size=small" class="img-responsive" />
	                                </div>
	                            </div>
	                            <div class="col-md-9">
	                                <div class="user-card-content">
	                                    <div class="user-name"><a href="#"><span ng-bind="lead.employeeAssigned.name"></span></a></div>
	                                    <div class="user-email"><i class="fa fa-envelope"></i> <a ng-href="mailto:{{lead.employeeAssigned.email}}"><span ng-bind="lead.employeeAssigned.email"></span></a></div>
	                                    <div class="user-phone"><i class="fa fa-phone"></i> <a ng-href="tel:{{lead.employeeAssigned.phone}}"><span ng-bind="lead.employeeAssigned.phone"></span></a> </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="row" ng-show=" !changeEmployeeMode">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-xs btn-link" ng-click="changeEmployee()"><span ng-show="lead.employeeAssigned">Change</span><span ng-show="lead.employeeAssigned==null   ">Assign</span> Employee</button>
                            </div>
                        </div>
                        <div class="row margin-top-5" ng-show="changeEmployeeMode">

                            <div class="col-md-12 text-center typeahead-container">
                                <input type="text" ng-model="userToAssign.user" placeholder="Employee Name" uib-typeahead="user as user.name for user in employeeList | filter:{name:$viewValue}" class="form-control" typeahead-show-hint="true" typeahead-min-length="0">
                                <button type="button" class="btn btn-default btn-xs pull-right" ng-click="cancelChangeEmployee()"><i class="fa fa-close"></i></button>
                                <button type="button" class="btn btn-success btn-xs pull-right" ng-click="submitAssignEmployee()" ng-disabled="!userToAssign.user.id"><i class="fa fa-check"></i></button>

                            </div>
                        </div>
					</div>


                </div>
                
                <!-- updated code for assigning caregiver -->
                <div class="lead-widget widget-assignee">
                    <div class="header">
                        <span>Auto Suggested Primary-CG</span>
                    </div>
                    <div class="widget-content">
                       <div class="row">
                            <div class="col-md-12 text-center" ng-show="changeCGMode">
                                <a class="btn btn-xs btn-link" href="<% url('/lead/'.$model['lead']->id.'/vendor/suggestions?filter=auto') %>"><span>Assign</span> Care-giver</a>
                            </div>
                        </div> 
                    </div>
                    <div class="widget-content">
                        <div class="user-card" ng-show="lead.primaryVendorAssigned.id">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="user-card-image">
                                        <img ng-src="{{lead.primaryVendorAssigned.imageUrl}}?size=small" class="img-responsive" />
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="user-card-content">
                                        <div class="user-name"><a href="#"><span ng-bind="lead.primaryVendorAssigned.name"></span></a></div>
                                        <div class="user-phone"><i class="fa fa-phone"></i> <a ng-href="tel:{{lead.primaryVendorAssigned.phone}}"><span ng-bind="lead.primaryVendorAssigned.phone"></span></a> </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" ng-show=" !changeCGMode">
                            <div class="col-md-12 text-center">
                                <a class="btn btn-xs btn-link" href="<% url('/lead/'.$model['lead']->id.'/vendor/suggestions?filter=auto') %>"><span ng-show="lead.primaryVendorAssigned">Change</span><span ng-show="lead.primaryVendorAssigned==null">Assign</span> Care-giver</a>
                            </div>
                        </div>
                        <div class="row margin-top-5" ng-show="changeCGMode">

                            <div class="col-md-12 text-center typeahead-container">
                                <input type="text" ng-model="userToAssign.user" placeholder="Employee Name" uib-typeahead="user as user.name for user in employeeList | filter:{name:$viewValue}" class="form-control" typeahead-show-hint="true" typeahead-min-length="0">
                                <button type="button" class="btn btn-default btn-xs pull-right" ng-click="cancelChangeEmployee()"><i class="fa fa-close"></i></button>
                                <button type="button" class="btn btn-success btn-xs pull-right" ng-click="submitAssignEmployee()" ng-disabled="!userToAssign.user.id"><i class="fa fa-check"></i></button>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="lead-widget widget-assignee">
                    <div class="header">
                        <span>Primary Care-giver Assigned</span>
                        <div class="pull-right">
                            <button class="btn btn-link btn-xs ng-hide" ng-click="assignCG()">
                                <i class="fa fa-edit"></i>
                            </button>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div class="user-card" ng-show="lead.primaryVendorAssigned.id">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="user-card-image">
                                        <img ng-src="{{lead.primaryVendorAssigned.imageUrl}}?size=small" class="img-responsive" />
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="user-card-content">
                                        <div class="user-name"><a href="#"><span ng-bind="lead.primaryVendorAssigned.name"></span></a></div>
                                        <div class="user-phone"><i class="fa fa-phone"></i> <a ng-href="tel:{{lead.primaryVendorAssigned.phone}}"><span ng-bind="lead.primaryVendorAssigned.phone"></span></a> </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" ng-show=" !changeCGMode">
                            <div class="col-md-12 text-center">
                                <a class="btn btn-xs btn-link" href="<% url('/lead/'.$model['lead']->id.'/vendor/suggestions') %>"><span ng-show="lead.primaryVendorAssigned">Change</span><span ng-show="lead.primaryVendorAssigned==null">Assign</span> Care-giver</a>
                            </div>
                        </div>
                        <div class="row margin-top-5" ng-show="changeCGMode">

                            <div class="col-md-12 text-center typeahead-container">
                                <input type="text" ng-model="userToAssign.user" placeholder="Employee Name" uib-typeahead="user as user.name for user in employeeList | filter:{name:$viewValue}" class="form-control" typeahead-show-hint="true" typeahead-min-length="0">
                                <button type="button" class="btn btn-default btn-xs pull-right" ng-click="cancelChangeEmployee()"><i class="fa fa-close"></i></button>
                                <button type="button" class="btn btn-success btn-xs pull-right" ng-click="submitAssignEmployee()" ng-disabled="!userToAssign.user.id"><i class="fa fa-check"></i></button>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="lead-widget widget-assignee">
	                <div class="header">
	                    <span>Backup Care-giver Assigned</span>
	                    <div class="pull-right">
	                        <button class="btn btn-link btn-xs ng-hide" ng-click="assignCG()">
	                            <i class="fa fa-edit"></i>
	                        </button>
	                    </div>
	                </div>

	                <div class="widget-content">
	                    <div class="user-card" ng-show="lead.vendorAssigned.id">
	                        <div class="row">
	                            <div class="col-md-3">
	                                <div class="user-card-image">
	                                    <img ng-src="{{lead.vendorAssigned.imageUrl}}?size=small" class="img-responsive" />
	                                </div>
	                            </div>
	                            <div class="col-md-9">
	                                <div class="user-card-content">
	                                    <div class="user-name"><a href="#"><span ng-bind="lead.vendorAssigned.name"></span></a></div>
	                                    <div class="user-phone"><i class="fa fa-phone"></i> <a ng-href="tel:{{lead.vendorAssigned.phone}}"><span ng-bind="lead.vendorAssigned.phone"></span></a> </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>

	                    <div class="row" ng-show=" !changeCGMode">
	                        <div class="col-md-12 text-center">
	                            <a class="btn btn-xs btn-link" href="<% url('/lead/'.$model['lead']->id.'/vendor/suggestions') %>"><span ng-show="lead.vendorAssigned">Change</span><span ng-show="lead.vendorAssigned==null   ">Assign</span> Care-giver</a>
	                        </div>
	                    </div>
	                    <div class="row margin-top-5" ng-show="changeCGMode">

	                        <div class="col-md-12 text-center typeahead-container">
	                            <input type="text" ng-model="userToAssign.user" placeholder="Employee Name" uib-typeahead="user as user.name for user in employeeList | filter:{name:$viewValue}" class="form-control" typeahead-show-hint="true" typeahead-min-length="0">
	                            <button type="button" class="btn btn-default btn-xs pull-right" ng-click="cancelChangeEmployee()"><i class="fa fa-close"></i></button>
	                            <button type="button" class="btn btn-success btn-xs pull-right" ng-click="submitAssignEmployee()" ng-disabled="!userToAssign.user.id"><i class="fa fa-check"></i></button>

	                        </div>
	                    </div>
	                </div>
	            </div>
                <div class="lead-widget widget-assignee">
                    <div class="header">
                        <span>QC Assigned</span>

                    </div>

                    <div class="widget-content">
                        <div class="user-card" ng-show="lead.qcAssigned.id">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="user-card-image">
                                        <img ng-src="{{lead.qcAssigned.imageUrl}}?size=small" class="img-responsive" />
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="user-card-content">
                                        <div class="user-name"><a href="#"><span ng-bind="lead.qcAssigned.name"></span></a></div>
                                        <div class="user-email"><i class="fa fa-envelope"></i> <a ng-href="mailto:{{lead.qcAssigned.email}}"><span ng-bind="lead.qcAssigned.email"></span></a></div>
                                        <div class="user-phone"><i class="fa fa-phone"></i> <a ng-href="tel:{{lead.qcAssigned.phone}}"><span ng-bind="lead.qcAssigned.phone"></span></a> </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" ng-show=" !changeQcEmployeeMode">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-xs btn-link" ng-click="changeQcEmployee()"><span ng-show="lead.qcAssigned">Change</span><span ng-show="lead.employeeAssigned==null   ">Assign</span> QC Employee</button>
                            </div>
                        </div>
                        <div class="row margin-top-5" ng-show="changeQcEmployeeMode">

                            <div class="col-md-12 text-center typeahead-container">
                                <input type="text" ng-model="qcToAssign.user" placeholder="QC Name" uib-typeahead="user as user.name for user in employeeList | filter:{name:$viewValue}" class="form-control" typeahead-show-hint="true" typeahead-min-length="0">
                                <button type="button" class="btn btn-default btn-xs pull-right" ng-click="cancelChangeQcEmployee()"><i class="fa fa-close"></i></button>
                                <button type="button" class="btn btn-success btn-xs pull-right" ng-click="submitAssignQcEmployee()" ng-disabled="!qcToAssign.user.id"><i class="fa fa-check"></i></button>

                            </div>
                        </div>
                    </div>


                </div>

                <div class="lead-widget widget-assignee">
                    <div class="header">
                        <span>Field Executive Assigned</span>

                    </div>

                    <div class="widget-content">
                        <div class="user-card" ng-show="lead.fieldAssigned.id">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="user-card-image">
                                        <img ng-src="{{lead.fieldAssigned.imageUrl}}?size=small" class="img-responsive" />
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="user-card-content">
                                        <div class="user-name"><a href="#"><span ng-bind="lead.fieldAssigned.name"></span></a></div>
                                        <div class="user-email"><i class="fa fa-envelope"></i> <a ng-href="mailto:{{lead.fieldAssigned.email}}"><span ng-bind="lead.fieldAssigned.email"></span></a></div>
                                        <div class="user-phone"><i class="fa fa-phone"></i> <a ng-href="tel:{{lead.fieldAssigned.phone}}"><span ng-bind="lead.fieldAssigned.phone"></span></a> </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" ng-show=" !changeFieldEmployeeMode">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-xs btn-link" ng-click="changeFieldEmployee()"><span ng-show="lead.fieldAssigned">Change</span><span ng-show="lead.fieldAssigned==null   ">Assign</span> Field Employee</button>
                            </div>
                        </div>
                        <div class="row margin-top-5" ng-show="changeFieldEmployeeMode">

                            <div class="col-md-12 text-center typeahead-container">
                                <input type="text" ng-model="fieldToAssign.user" placeholder="Field Employee Name" uib-typeahead="user as user.name for user in employeeList | filter:{name:$viewValue}" class="form-control" typeahead-show-hint="true" typeahead-min-length="0">
                                <button type="button" class="btn btn-default btn-xs pull-right" ng-click="cancelChangeFieldEmployee()"><i class="fa fa-close"></i></button>
                                <button type="button" class="btn btn-success btn-xs pull-right" ng-click="submitAssignFieldEmployee()" ng-disabled="!fieldToAssign.user.id"><i class="fa fa-check"></i></button>

                            </div>
                        </div>
                    </div>


                </div>


                <div class="lead-widget widget-enquiry-date">
                    <div class="widget-content">
                        <div>
                            <strong>Status: </strong>
                            <span ng-bind="lead.operationStatus.label"></span>
                        </div>
                        <div>
                            <strong>Enquiry Date: </strong>
                            <span>{{lead.enquiryDate | carbondate}}</span>
                        </div>
                        <div>
                            <strong>Start Date: </strong>
                            <span>{{lead.startDate}}</span>
                        </div>
                        <div>
                            <strong>Close Date: </strong>
                            <span>{{(lead.closeDate | carbondate) || 'NA'}}</span>
                        </div>
                        <div>
                            <strong>Price: </strong>
                            <span>{{(lead.payment.price) || 'None'}}</span>
                        </div>
                        <div>
                            <strong>Price Unit: </strong>
                            <span>{{(lead.priceUnit.label) || 'None'}}</span>
                        </div>
                        <div>
                            <strong>Payment Type: </strong>
                            <span ng-bind="lead.paymentType.label"></span>
                        </div>
                        <div>
                            <strong>Payment Period: </strong>
                            <span ng-bind="lead.paymentPeriod.label"></span>
                        </div>
                        <div>
                            <strong>Payment Mode: </strong>
                            <span ng-bind="lead.paymentMode.label"></span>
                        </div>
                    </div>
                </div>
                <div class="lead-widget widget-enquiry-date">
					<div class="header">
						<div class="title">Other Detail</div>
					</div>
					<div>
						<strong>House No/Address</strong>
						<span ng-bind="lead.address"></span>
					</div>
					<div>
                        <strong>Locality</strong>
                        <span ng-bind="lead.locality.formattedAddress"></span>
                    </div>
                    <div>
                        <strong>Landmark</strong>
                        <span ng-bind="lead.landmark"></span>
                    </div>
					<div>
                        <strong>Phone</strong>
                        <span ng-bind="lead.phone"></span>
                    </div><div>
                        <strong>Email</strong>
                        <span ng-bind="lead.email || 'NA'"></span>
                    </div>
					<div>
                        <strong>Lead Source</strong>
                        <span ng-bind="lead.leadSource.label"></span>
                    </div>
					<div>
                        <strong>Reference</strong>
                        <span ng-bind="lead.leadReference.label"></span>
                    </div>
					<div ng-show="lead.remark">
                        <strong>Remark</strong>
                        <span ng-bind="lead.remark"></span>
                    </div>
                </div>
                <div class="lead-widget widget-status ng-hide">
					<div class="title">Status Date</div>
                </div>
                
                <div>
                    <h2>Feedbacks</h2>
                        <table class="table">
                            <thead>
                              <tr>
                                <th>Date</th>
                                <th>CG</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Created By</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($model['leadFeedback'] as $feedback)
                                <tr>                                  
                                  <td>{!!Carbon\Carbon::parse($feedback->feedback_date)->format('d-m-Y')!!}</td>
                                  <td>{!!$feedback->caregiver_name!!}</td>
                                  <td>{!!$feedback->status!!}</td>
                                  <td title="{!!$feedback->remarks!!}">{!!str_limit($feedback->remarks,10)!!}</td>
                                  <td>{!!$feedback->created_by_name!!}</td>
                                </tr>  
                              @endforeach
                            </tbody>
                        </table>			
                </div>

            </div>

        </div>
    </div>

    <script type="text/ng-template" id="changeLeadStatusModalTemplate.html">
        <div class="modal-header">
            <h3 class="modal-title">Update Lead Status</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="sub-title">Choose Status</div>
                    <div>
                        <select tabindex="-1" class="select2-hidden-accessible" aria-hidden="true" ng-model="statusChangeObject.status">
                            <optgroup label="{{groupItem.label}}" ng-repeat="groupItem in statusesGrouped">
                                <option value="{{status.id}}" ng-repeat="status in groupItem.statuses"><span ng-bind="status.label"></span>{{status.label}}</option>
                            </optgroup>
                        </select>
                            <span ng-message="status"></span>
                    </div>
                    <div ng-show="statusChangeObject.status.id">
                        <select tabindex="-1" class="select2-hidden-accessible" aria-hidden="true" ng-model="statusChangeObject.reason">
                            <option value="{{reason.id}}" ng-repeat="reason in statusChangeObject.status.reasons"><span ng-bind="reason.label"></span></option>
                        </select>
                            <span ng-message="statusChangeObject.reason"></span>
                    </div>
                    <div class="sub-title">Employee Assignment</div>

                    <div>
                        <input type="text" ng-model="statusChangeObject.user" placeholder="Employee Name" uib-typeahead="user as user.name for user in employeeList | filter:{name:$viewValue}" class="form-control" typeahead-show-hint="true" typeahead-min-length="0">

                    </div>
                    <div class="sub-title">Any Comment</div>
					<div>
						<textarea class="form-control" ng-model="statusChangeObject.comment" rows="3"></textarea>
					</div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-disabled="!statusChangeObject.status" ng-click="updateStatus()">Update</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>

    <script type="text/ng-template" id="validateLeadModalTemplate.html">
        <div class="modal-header">
            <h3 class="modal-title">Validate Lead</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="sub-title">Validation Ailment</div>
                    <div ng-show="ailments.length>0">
						<ui-select ng-model="validationData.ailment" theme="bootstrap">
	                         <ui-select-match placeholder="Select ailments">{{$select.selected.name}}</ui-select-match>
	                         <ui-select-choices repeat="item in ailments | filter: $select.search">
	                           <div ng-bind-html="item.name | highlight: $select.search"></div>
	                         </ui-select-choices>
	                    </ui-select>
                    </div>

<div class="spacer-5" ng-show="validationData.ailment.tasks.length>0"></div>
                    <div class="sub-title" ng-show="validationData.ailment.tasks.length>0">Ailment Tasks</div>
					<div class="row" ng-show="validationData.ailment.tasks.length>0">
						<div class="col-md-4" ng-repeat="task in validationData.ailment.tasks">
							<div>
	                            <input type="checkbox" id="checkbox-fa-light-1" ng-model="task.selected">
	                            <label for="checkbox-fa-light-1">
	                              <span ng-bind="task.label"></span>
	                            </label>
	                        </div>
						</div>
					</div>

                    <div class="spacer-5" ng-show="validationData.ailment.tasks.length>0"></div>
                    <div class="sub-title">Employee Assignment</div>

                    <div>
                        <input type="text" ng-model="statusChangeObject.user" placeholder="Employee Name" uib-typeahead="user as user.name for user in employeeList | filter:{name:$viewValue}" class="form-control" typeahead-show-hint="true" typeahead-min-length="0">

                    </div>
                    <div class="sub-title">Any Comment</div>
					<div>
						<textarea class="form-control" ng-model="statusChangeObject.comment" rows="3"></textarea>
					</div>

                </div>
            </div>
            <div class="col-md-"></div>
        </div>
        <div class="modal-footer">
	            <button class="btn btn-success" type="button" ng-click="updateStatus()" ng-disabled="!statusChangeObject.status">Submit</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>


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
    <script type="text/ng-template" id="deleteLeadModalTemplate.html">
        <div class="modal-header">
            <h3 class="modal-title">Delete Lead</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="sub-title">Do you realy want to delete this lead?</div>

                </div>
            </div>
            <div class="col-md-"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-click="deleteLead()">Yes Delete</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>
    <script type="text/ng-template" id="startLeadServiceModalTemplate.html">
        <div class="modal-header">
            <h3 class="modal-title">Start Service Confirmation</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 ">
					<h4>Do you agree to start service with following details:</h4>
					<p><strong>Employee Assigned: </strong><span ng-bind="lead.employeeAssigned.name"></span></p>
					<p><strong>Caregiver Assigned: </strong><span ng-bind="lead.vendorAssigned.name"></span></p>
					<p><strong>QC Assigned: </strong><span ng-bind="lead.qcAssigned.name"></span></p>
					<p><strong>Field Boy Assigned: </strong><span ng-bind="lead.fieldAssigned.name"></span></p>
                                        <p><strong>Payment Type: </strong><span ng-bind="lead.paymentType.label"></span></p>
                                        <p><strong>Payment Period: </strong><span ng-bind="lead.paymentPeriod.label"></span></p>
                                        <p><strong>Payment Mode: </strong><span ng-bind="lead.paymentMode.label"></span></p>
                                        <p><strong>Price Unit: </strong><span ng-bind="lead.priceUnit.label"></span></p>
                                        <p><strong>Price: </strong><span ng-bind="lead.price"></span></p>     
					
                                        <h5>Select CG you want to continue with: </h5>
					<div class="row">
						<div class="col-md-4">
							<input type="radio" ng-model="cgAssigned" ng-value="lead.primaryVendorAssigned.id" /> <span ng-bind="lead.primaryVendorAssigned.name"></span>
						</div>
						<div class="col-md-4" ng-show="showBackUpVendor">
							<input type="radio" ng-model="cgAssigned" ng-value="lead.vendorAssigned.id" /> <span ng-bind="lead.vendorAssigned.name"></span>
						</div>
						<div class="col-md-4">

						</div>
					</div>
					<hr />

					<div class="text-center">
						<input type="checkbox" ng-model="agreement" /> All the assignment are fine to start the service. Payment information
                                                    should be present to start the service.
					</div>
                </div>
            </div>
            <div class="col-md-"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" ng-disabled="!agreement" type="button" ng-click="startService()">Start Service</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>


	<script type="text/ng-template" id="closeLeadModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Close Service</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
					<div class="sub-title">Close or Hold?</div>

	                <div>
	                    <ui-select ng-model="formData.status" theme="bootstrap">
                        <ui-select-match placeholder="Select Closure">{{$select.selected.label}}</ui-select-match>
                        <ui-select-choices repeat="item in statuses | filter: $select.search">
                          <div ng-bind-html="item.label | highlight: $select.search"></div>
                        </ui-select-choices>
                      </ui-select>
	                </div>
	                <div class="sub-title">Date</div>
	                <div>
	                    <input type="date" ng-model="formData.date" name="statusdate">
	                </div>
	                <div class="sub-title">Choose Reason</div>
	                <div>
	                    <ui-select ng-model="formData.reason" theme="bootstrap" >
	                        <ui-select-match placeholder="Select Reason">{{$select.selected.label}}</ui-select-match>
	                        <ui-select-choices repeat="item in formData.status.reasons | filter: $select.search">
	                          <div ng-bind-html="item.label | highlight: $select.search"></div>
	                        </ui-select-choices>
	                    </ui-select>
	                </div>

	                <div class="sub-title" ng-show="formData.reason.label=='Unhappy With Service'">Select Service Issue</div>
	                <div ng-show="formData.reason.label=='Unhappy With Service'">
	                    <ui-select ng-model="formData.issue" theme="bootstrap" on-select="checkAskCGDeduction($item.id)">
	                        <ui-select-match placeholder="Select Issue">{{$select.selected.label}}</ui-select-match>
	                        <ui-select-choices repeat="item in serviceIssueList | filter: $select.search">
	                          <div ng-bind-html="item.label | highlight: $select.search"></div>
	                        </ui-select-choices>
	                    </ui-select>
	                </div>

                    <div ng-show="askAmountDeduction">
                        <div class="sub-title">Amount Deduction of CG (INR)</div>
                        <div>
                            <input type="text" ng-model="formData.deduction" name="deduction" class="form-control">
                        </div>
                    </div>

	                <div class="sub-title">Any Comment</div>
	                <div>
	                    <textarea class="form-control" ng-model="formData.comment" rows="3"></textarea>
	                </div>


                </div>
            </div>
            <div class="col-md-"></div>
        </div>
        <div class="modal-footer">

            <button class="btn btn-success" type="button" ng-click="updateStatus()" ng-disabled="!formData.reason">Update</button>

            <button class="btn btn-default" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>

    <script type="text/ng-template" id="notifyCustomerTemplate.html">
        <div class="modal-header">
            <h3 class="modal-title">Notify Customer</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">



					<div class="sub-title">Existing Templates</div>
	                <div>
	                    <ui-select ng-model="formData.template" on-select="templateSelected($item)" theme="bootstrap">
                        <ui-select-match placeholder="Select Notification Teamplate">{{$select.selected.label}}</ui-select-match>
                        <ui-select-choices repeat="item in notificationTemplates | filter: $select.search">
                          <div ng-bind-html="item.label | highlight: $select.search"></div>
                        </ui-select-choices>
                      </ui-select>
	                </div>
	                <div class="sub-title">Notification Header</div>
	                <div>
                        <input type="text" class="form-control" ng-model="formData.header" />
                    </div>
	                <div class="sub-title">Notification Message</div>
	                <div>
                        <textarea class="form-control" ng-model="formData.content" rows="3"></textarea>
                    </div>

					<div>
						<input type="checkbox" ng-model="formData.new_template" ng-true-value="1" ng-false-value="0"> Create New Tempalate
					</div>
	                <div class="sub-title" ng-show="formData.new_template==1">Template Name</div>
	                <div ng-show="formData.new_template==1">
                        <input type="text" class="form-control" ng-model="formData.new_template_name" />
                    </div>



                </div>
            </div>
            <div class="col-md-"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-click="sendNotificationToCustomer()" ng-disabled="formData.content==''">Send</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>
    <script type="text/ng-template" id="markAttendanceLeadModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Mark CG Attendance</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
					<div class="sub-title">Attendance Date</div>
	                <div>
	                    <input type="date" class="form-control" ng-model="formData.date" />
	                </div>

	                <div class="sub-title">CG Attendance</div>
                    <div>
                        <div class="btn-group">
                            <label class="btn btn-success" ng-model="formData.attendance" uib-btn-radio="true">Present</label>
                            <label class="btn btn-success" ng-model="formData.attendance" uib-btn-radio="false">Absent</label>
                        </div>
                    </div>



	            </div>
	            <div class="col-md-6">
	                <div class="sub-title">Care Giver</div>
	                <div class="form-group" ng-show="primaryCGAssigned">
	                    <!--<input type="radio" ng-model="formData.assignCaregiver" value="primaryCG" />-->
	                    <strong ng-bind="primaryCGAssigned.name"></strong> (Primary Caregiver Assigned)
	                </div>
	                <!--
	                 <div class="form-group" ng-show="backUpCGAssigned">
                        <input type="radio" ng-model="formData.assignCaregiver" value="backupCG" />
                        <strong ng-bind="backUpCGAssigned.name"></strong> (Backup Caregiver Assigned)
                    </div>
                    <div class="form-group other-caregiver-typeahead-container">
                    	                    <input type="radio" ng-model="formData.assignCaregiver" value="otherCG" /> Other Caregiver from list
                    	                    <div ng-show="formData.assignCaregiver =='otherCG'">

                    	                            <input  type="text" ng-model="formData.caregiver" placeholder="Select Caregiver" uib-typeahead="user as user.name for user in caregiverList | filter:{name:$viewValue}" class="form-control" typeahead-show-hint="true" typeahead-min-length="0">


                    	                    </div>
                    	                </div>
                    	                <div class="sub-title">Care Giver Price For Day <small>(in Rs)</small></div>
                                        <div>
                                            <input type="number" class="form-control" ng-model="formData.price" />
                                        </div>
	                -->
					<a ng-href="{{careGiverAssignmentLink}}" class="btn btn-sm btn-success">Replace Caregiver</a>
	            </div>
	        </div>

	        <div class="row">
	            <div class="col-md-12">
	                <div class="sub-title">Any Comment</div>
                    <div>
                        <textarea class="form-control" ng-model="formData.comment" rows="3"></textarea>
                    </div>
	            </div>
            </div>


        </div>
        <div class="modal-footer">

            <button class="btn btn-success" type="button" ng-click="markAttendance()" ng-disabled="formData.caregiver">Update</button>

            <button class="btn btn-default" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>



@endsection

@section('pageLevelJS')

<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>



@endsection