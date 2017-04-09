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
    var leadId = '<% $model['leadId'] %>';</script>
<style>
    .grid {
        width: 100%;
    }
</style>
<div ng-controller="operationLeadVendorAutoSuggestionCtrl" id="lead-view">
    <div class="page-title">
        <div class="title">
            <span>Lead: <% $model['lead']->customer_name%></span>
            <small ng-show="lead.patient.name">({{lead.patient.name}})</small>
            <span class="badge badge-success badge-large bg-success"><a href="/lead/<% $model['leadId'] %>/vendor/suggestions/reload">Reload Vendors</a></span>
            <span class="badge badge-success badge-large bg-success"><a href="/lead/<% $model['leadId'] %>/vendor/suggestions/deactive/{{vendorData.id}}">Switch to Next Vendor</a></span>
            <div class="pull-right">
                <span class="badge badge-success badge-large bg-success" ng-bind="lead.service.name"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="grid1" ui-grid="gridOptions" class="grid"></div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <i class="fa fa-stop grid-cell-cg-isblacklisted"></i> <span>CG is Blacklisted</span>
                </div>
            </div>
        </div>

    </div>
    <hr />
    <div class="row">
        <div class="col-md-4">
            <div class="card lead-widget patient-info">
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
                                    <span ng-bind="lead.patient.age" ng-show="lead.patient.age > 0"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    Weight
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <span ng-bind="lead.patient.weight"  ng-show="lead.patient.weight > 0"></span>
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
                                    Shift Required
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <span ng-bind="lead.patient.shiftRequired.label"></span>
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
                                        <span ng-bind="lead.patient.equipment.name"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Mobility
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.mobility.label"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Illness
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.illness"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Physical Condition
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.physicalCondition"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Morning Wakeup Time
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-show="lead.patient.morningWakeUpTime!='00:00:00'" ng-bind="lead.patient.morningWakeUpTime"></span>
                                        <span ng-show="lead.patient.morningWakeUpTime=='00:00:00'">Not Available</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Walking Time
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-show="lead.patient.walkTiming!='00:00:00'" ng-bind="lead.patient.walkTiming"></span>
                                        <span ng-show="lead.patient.walkTiming=='00:00:00'">Not Available</span>
                                    </div>
                                </div>
                                <div class="row ng-hide">
                                    <div class="col-md-6 col-sm-6">
                                        Walking Location
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.walkLocation"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Breakfast Time
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-show="lead.patient.breakfastTime!='00:00:00'" ng-bind="lead.patient.breakfastTime"></span>
                                        <span ng-show="lead.patient.breakfastTime=='00:00:00'">Not Available</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Lunch Time
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-show="lead.patient.lunchTime!='00:00:00'" ng-bind="lead.patient.lunchTime"></span>
                                        <span ng-show="lead.patient.lunchTime=='00:00:00'">Not Available</span>
                                    </div>
                                </div>
                                <div class="row">
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
                                            <span ng-show="lead.patient.genderPreferred == 'F'">Female</span>
                                            <span ng-show="lead.patient.genderPreferred == 'M'">Male</span>
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

        </div>
        <div class="col-md-4">
            <div class="lead-widget widget-enquiry-date">
                <div class="widget-content">
                    <div>
                        <strong>Status: </strong>
                        <span ng-bind="lead.operationStatus.label"></span>
                    </div>
                    <div>
                        <strong>Enquiry Date: </strong>
                        <span>{{lead.enquiryDate| carbondate}}</span>
                    </div>
                    <div>
                        <strong>Start Date: </strong>
                        <span>{{lead.startDate}}</span>
                    </div>
                    <div>
                        <strong>Close Date: </strong>
                        <span>{{lead.closeDate| carbondate}}</span>
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
                    <strong>Address</strong>
                    <span ng-bind="lead.address"></span>
                </div>
                <div>
                    <strong>Locality</strong>
                    <span ng-bind="lead.locality.formattedAddress"></span>
                </div>
                <div>
                    <strong>Phone</strong>
                    <span ng-bind="lead.phone"></span>
                </div><div>
                    <strong>Email</strong>
                    <span ng-bind="lead.email"></span>
                </div>
                <div>
                    <strong>Lead Source</strong>
                    <span ng-bind="lead.leadSource.label"></span>
                </div>
                <div>
                    <strong>Reference</strong>
                    <span ng-bind="lead.leadReference.label"></span>
                </div>
                <div>
                    <div><strong>Remark</strong></div>
                    <span ng-bind="lead.remark"></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="lead-widget widget-assignee">
                <div class="header">
                    <span>Employee Assigned</span>

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


                </div>


            </div>
            <div class="lead-widget widget-assignee" ng-show="lead.primaryVendorAssigned.id">
                <div class="header">
                    <span>Care-giver Assigned</span>
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
                </div>


            </div>
            <div class="lead-widget widget-assignee" ng-show="lead.vendorAssigned.id">
                <div class="header">
                    <span>Back-up Care-giver Assigned</span>
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
                </div>


            </div>



        </div>

    </div>
</div>



<script type="text/ng-template" id="viewVendorProfile.html">
    <div class="modal-header">
    <h3 class="modal-title" ng-bind="vendor.name"></h3>
    </div>
    <div class="modal-body">
    <div class="row">
    <div class="col-md-12">
    <div class="row">
    <div class="col-md-6">
    <div class="sub-title">Id : <span ng-bind="vendor.id"></span></div>
    <div class="sub-title">Age : <span ng-bind="vendor.age"></span></div>
    <div class="sub-title">Mobile : <span ng-bind="vendor.mobile"></span></div>
    </div>
    <div class="col-md-6">
    <div class="sub-title">Weight : <span ng-bind="vendor.weight"></span></div>
    <div class="sub-title">Height : <span ng-bind="vendor.height"></span></div>
    <div class="sub-title">Religion : <span ng-bind="vendor.religion.label"></span></div>
    <div class="sub-title">XYZ : <span ng-bind="vendor.level"></span></div>
    </div>
    </div>
    </div>
    </div>
    <div class="col-md-"></div>
    </div>
    <div class="modal-footer">
    <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
    </div>
</script>

<script type="text/ng-template" id="assignVendorToLead.html">
        <div class="modal-header">
            <h3 class="modal-title" ng-bind="vendor.name"></h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">

                    <div ng-if="complaintData.operationStatusGroupId == 1 && lead.primaryVendorAssigned">

                        <div class="row">* You need to raise a complaint below for this pre-placement replacement.</div>

                        <div class="row" style="margin-top: 15px;">
                            <h4 class="text-center">Old CG Complaint Details</h4>

                            <div class="col-md-4"><h5>Complaint Sub Category</h5></div>
                            <div class="col-md-8">
                                <ui-select ng-show="complaintSubCategories.length" ng-model="complaintData.complaintSubCategories" theme="bootstrap" ng-required="required" on-select="getComplaintSubChildCategories($item.id)">
                                    <ui-select-match placeholder="Select Sub-Category">{{$select.selected.name}}</ui-select-match>
                                    <ui-select-choices repeat="item.id as item in complaintSubCategories">
                                        {{item.name}}
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-4"><h5></h5></div>
                            <div class="col-md-8">
                                <ui-select ng-show="complaintSubChildCategories.length" ng-model="complaintData.complaintSubChildCategories" theme="bootstrap" ng-required="required">
                                    <ui-select-match placeholder="Select child-Category">{{$select.selected.name}}</ui-select-match>
                                    <ui-select-choices repeat="item.id as item in complaintSubChildCategories">
                                        {{item.name}}
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><h5>Pre Placement Complaint Detail</h5></div>
                            <div class="col-md-8">
                                <textarea ng-model="complaintData.details" type="text" class="form-control"></textarea>
                            </div>
                        </div>

                        <hr>

                    </div>


                    <h4 class="text-center">Replacement CG Details</h4>
                    <div class="row">
                        <div class="col-md-4"><h5>Price Per Day </h5></div>
                        <div class="col-md-8">
                            <input ng-model="cgPrice" type="text" class="form-control" />
                        </div>
                    </div>

                    <h5>Please select the task as per care-giver expertise:</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div ng-repeat="taskCat in validatedData.tasks" ng-if="showTaskCategory(taskCat)">
                        <h5 class="title" ng-bind="taskCat.label"></h5>
                        <div class="row">
                            <div class="col-md-6" ng-repeat="task in taskCat.tasks" ng-if="task.selected">
                                <div>
                                    <input type="checkbox" id="checkbox-task-{{task-id}}"  ng-model="task.sourcingSelected">
                                    <label for="checkbox-task-{{task-id}}">
                                      <span ng-bind="task.label"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-"></div>
        </div>
        <div class="modal-footer">
            <div class="pull-left">

            </div>
             <button class="btn btn-success" type="button" ng-disabled="!isAssignable() || !cgPrice" ng-click="assignCareGiverToLead(true)">
                <span>Assign as</span>
                <span ng-show="lead.primaryVendorAssigned && lead.primaryVendorAssigned.length">Assign as</span>
                <span>Primary Care-Giver</span>
             </button>
             <button class="btn btn-success" type="button" ng-disabled="!isAssignable()||!cgPrice" ng-click="assignCareGiverToLead(false)">Assign As Backup Care-Giver</button>
             <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>


<script type="text/ng-template" id="vendorAvailabilityModalTemplate.html">
    <div class="modal-header">
        <h3 class="modal-title">Edit Availability For <span ng-bind="vendorData.name"></span></h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 text-center">
                <label class="btn btn-success check-selectable"  ng-model="availability.available" uib-btn-radio="1">Available</label>
                <label class="btn btn-success check-selectable" ng-model="availability.available" uib-btn-radio="0">Un-Available</label>
            </div>


        </div>
    <div class="row spacer-5">
        <div class="col-sm-8 col-sm-offset-2" ng-show="availability.available!=null">
            <label>Select Option for <span ng-show="availability.available==1">Availability</span><span ng-show="availability.available==0">Un-availability</span></label>
            <ui-select ng-model="availability.option" theme="bootstrap">
            <ui-select-match placeholder="Select Option">{{$select.selected.label}}</ui-select-match>
                <ui-select-choices repeat="item in availabilityOptions | filter: $select.search | filter: {is_available : availability.available}">
                        <div ng-bind-html="item.label | highlight: $select.search"></div>
                </ui-select-choices>
            </ui-select>
        </div>
    </div>
    <div class="row spacer-5" ng-show="availability.option.reasons.length>0">
        <div class="col-sm-8 col-sm-offset-2" ng-show="availability.available==0 ||availability.available==1">
            <label>Reason for: <span ng-bind="availability.option.label"></span></label>
            <ui-select ng-model="availability.reason" theme="bootstrap">
            <ui-select-match placeholder="Select Option">{{$select.selected.label}}</ui-select-match>
            <ui-select-choices repeat="item in availability.option.reasons | filter: $select.search">
            <div ng-bind-html="item.label | highlight: $select.search"></div>
            </ui-select-choices>
            </ui-select>

        </div>
    </div>
    <div class="row spacer-5" >
        <div class="col-sm-8 col-sm-offset-2" >
        <div ng-show="availability.reason.slug =='different-shift'">
            <label>Select Shift</label>
            <ui-select ng-model="availability.shift" ng-show="availability.reason.slug =='different-shift'" theme="bootstrap">
                <ui-select-match placeholder="Select Shift">{{$select.selected.label}}</ui-select-match>
                    <ui-select-choices repeat="item in dataMapper.shifts | filter: $select.search">
                        <div ng-bind-html="item.label | highlight: $select.search"></div>
                    </ui-select-choices>
            </ui-select>
        </div>


    <div></div>
    <div ng-show="availability.reason.slug =='different-location'" >
        <label>Select Location Zone</label>
        <ui-select ng-model="availability.location" theme="bootstrap">
        <ui-select-match placeholder="Select Location Zone">{{$select.selected.label}}</ui-select-match>
        <ui-select-choices repeat="item in dataMapper.zones | filter: $select.search">
        <div ng-bind-html="item.label | highlight: $select.search"></div>
        </ui-select-choices>
        </ui-select>
    </div>
    <div></div>
    <div class="form-group" ng-show="availability.reason.slug =='other-issue'">
        <label>What's the issue?</label>
        <input type="text" ng-model="availability.otherReason" placeholder="" class="form-control" />
    </div>
    <div></div>

    <div class="form-group" ng-show="availability.option.slug =='date-available' || availability.option.slug =='employed-somewhere'">
        <label ng-show="availability.option.slug =='date-available'">Select date from when he/she is available to work</label>
        <label ng-show="availability.option.slug =='employed-somewhere'">Select date from upto when he/she is deployed</label>
        <input type="date" ng-model="availability.date" placeholder="Select Date" class="form-control" />
    </div>
    </div>
    </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success" type="button" ng-click="updateVendorLocality()">Update</button>
        <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
    </div>
</script>
@endsection

@section('pageLevelJS')

<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/vendorscript.js')%>"></script>


@endsection