<?php
$menuSelected = "complaints";
$angularModule = 'adminModule';
?>
@extends('layouts.admin.master')



@section('title')
Complaints
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
<div ng-controller="adminComplaintsCtrl" id="adminLeadView">
	<div class="page-title">
        <span class="title">Complaints</span>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" ng-class="{'active':tabActive=='userComplaintsTab'}"><a href="#" aria-controls="home" ng-click="openTab('userComplaintsTab')" aria-expanded="true">User Complaints</a></li>
                    <li role="presentation" ng-class="{'active':tabActive=='cgComplaintsTab'}"><a href="#" aria-controls="profile" ng-click="openTab('cgComplaintsTab')" aria-expanded="false">CG Complaints</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-panes" ng-if="tabActive=='userComplaintsTab'">
                        <div class="table-responsive">
                           <div id="grid1" ui-grid="gridOptionsComplaintsUser" class="grid"></div>
                        </div>
                   </div>
                   <div role="tabpanel" class="tab-panes" ng-if="tabActive=='cgComplaintsTab'">
                        <div class="table-responsive">
                            <div id="grid2" ui-grid="gridOptionsComplaintsCg" class="grid"></div>
                        </div>
                   </div>
                </div>
            </div>

        </div>
    </div>
    <div class="new-lead-button">
        <md-button class="md-fab md-primary" aria-label="Use Android" ng-click="createComplaint($event,'','')">
          <i class="fa fa-plus"></i>
        </md-button>
    </div>
</div>


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