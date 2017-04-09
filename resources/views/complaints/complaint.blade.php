<?php
$menuSelected = "complaints";
$angularModule = 'adminModule';

?>
@extends('layouts.admin.master')

@section('title')
Complaint
@endsection

@section('content')

    <script>
        var complaintId = "<% $model['complaintId'] %>";
        var userType = "<% $model['userType'] %>";
    </script>
    <style>
        .grid {
          width: 100%;
        }
        .mb6{
            margin-bottom: 6px;
        }
    </style>

    <div ng-controller="adminComplaintCtrl" id="lead-view">
        <div class="page-title">
            <div class="title">
                <span>Complaint: {{ complaintData.user_name }} ({{ complaintData.complaint_user_label }})</span>
                <div class="pull-right">
                    <!-- <button class="btn btn-sm btn-default" ng-click="deleteLead()" ng-show="!isLeadValidated()">Delete Lead</button> -->
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card patient-info">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Complaint Info</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Complaint ID
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        {{ complaintData.id }}
                                    </div>
                                </div>
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Name
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        {{ complaintData.user_name }} ({{ complaintData.complaint_user_label }})
                                    </div>
                                </div>
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Phone
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        {{ complaintData.phone }}
                                    </div>
                                </div>
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Lead ID
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        {{ complaintData.lead_id }}
                                    </div>
                                </div>
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Complaint Category
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        {{ complaintData.category_name }}
                                    </div>
                                </div>
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Complaint Details
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        {{ complaintData.details }}
                                    </div>
                                </div>
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Complaint Raised At
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        {{ complaintData.created_at }}
                                    </div>
                                </div>
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Complaint For
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        {{ complaintData.complaint_to_name }} ({{ complaintData.complaint_to_phone }}) ({{ complaintData.complaint_to_type_label }})
                                    </div>
                                </div>
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Status
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span class="badge badge-success badge-large bg-success">{{ complaintData.status }}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card patient-info">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Actions</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Change Status
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <select ng-model="changeStatusData.complaintStatus" ng-options="complaintStatus.id as complaintStatus.label for complaintStatus in complaintStatuses">
                                        </select>
                                        <button class="btn btn-success btn-sm" ng-click="changeComplaintStatus()">Change Status</button>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="col-md-12" ng-if="complaintData.complaint_user_label == 'Customer'">
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6">
                                        <button class="btn btn-success btn-sm" ng-click="resolutionCGTraining(complaintData)">CG Training</button>
                                        <!-- <button class="btn btn-success btn-sm" ng-click="resolutionCGCounselling()">CG Counselling</button> -->
                                        <button class="btn btn-success btn-sm" ng-click="resolutionCGReplacement(complaintData)">CG Replacement</button>
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-12" ng-if="complaintData.complaint_user_label == 'Customer'">
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6">
                                        <button class="btn btn-primary btn-sm" ng-click="historyCGTraining(complaintData)">Training History</button>
                                        <button class="btn btn-primary btn-sm" ng-click="historyCGReplacement(complaintData)">Replacement History</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row mb6">
                                    <div class="col-md-6 col-sm-6">
                                        Add Status (log)
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div>
                                            <textarea ng-model="logData.comment" class="form-control"></textarea>
                                        </div>
                                        <button class="btn btn-success" type="button" ng-click="addComplaintLog()" ng-disabled="!logData.complaintId || (logData.comment=='')">Save</button>
                                        <div id="logSucess" class="alert alert-info" style="margin-top: 10px;display: none;">Log Added Successfully.</div>
                                    </div>
                                </div>
                                
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <script type="text/ng-template" id="resolutionCGTrainingModal.html">
        <div class="modal-header">
            <h3 class="modal-title">CG Training</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="sub-title">CG Name</div>
                    <div>
                        <ui-select ng-model="trainingFormData.cgId" theme="bootstrap">
                            <ui-select-match placeholder="Select Caregiver">{{$select.selected.name}} - {{$select.selected.phone}} - {{$select.selected.email}}</ui-select-match>
                            <ui-select-choices refresh="searchuser($select.search)" refresh-delay="0" minimum-input-length="3" repeat="item.id as item in usersList | filter: $select.search">
                                {{item.name}} - {{item.phone}} - {{item.email}} / {{item.id}}
                            </ui-select-choices>
                        </ui-select>
                    </div>

                    <div class="sub-title">Training Date</div>
                    <div>
                        <input type="date" class="form-control" ng-model="trainingFormData.trainingDate" />
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-click="submitResolutionCGTraining()" ng-disabled="!trainingFormData.cgId || !trainingFormData.trainingDate">Update</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>

    <script type="text/ng-template" id="resolutionCGReplacementModal.html">
        <div class="modal-header">
            <h3 class="modal-title">CG Replacement</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="sub-title">CG Name</div>
                    <div>
                        <ui-select ng-model="replacementFormData.cgId" theme="bootstrap">
                            <ui-select-match placeholder="Select Caregiver">{{$select.selected.name}} - {{$select.selected.phone}} - {{$select.selected.email}}</ui-select-match>
                            <ui-select-choices refresh="searchuser($select.search)" refresh-delay="0" minimum-input-length="3" repeat="item.id as item in usersList | filter: $select.search">
                                {{item.name}} - {{item.phone}} - {{item.email}} / {{item.id}}
                            </ui-select-choices>
                        </ui-select>
                    </div>

                    <div class="sub-title">Start Date</div>
                    <div>
                        <input type="date" class="form-control" ng-model="replacementFormData.startDate" />
                    </div>

                    <div class="sub-title">Replacement Date</div>
                    <div>
                        <input type="date" class="form-control" ng-model="replacementFormData.replacementDate" />
                    </div>

                    <div class="sub-title">Reason</div>
                    <div>
                        <textarea class="form-control" ng-model="replacementFormData.reason"></textarea>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-click="submitResolutionCGReplacement()" ng-disabled="!replacementFormData.cgId || !replacementFormData.startDate || !replacementFormData.replacementDate">Update</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>

    <script type="text/ng-template" id="historyCGTrainingModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Training History</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-responsive table-condensed">
                        <thead>
                            <tr>
                                <th>CG Name</th>
                                <th>CG Phone</th>
                                <th>Training Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="row in history">
                                <td>{{ row.name }}</td>
                                <td>{{ row.phone }}</td>
                                <td>{{ row.date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>

    <script type="text/ng-template" id="historyCGReplacementModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Training History</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-responsive table-condensed">
                        <thead>
                            <tr>
                                <th>CG Name</th>
                                <th>CG Phone</th>
                                <th>Start Date</th>
                                <th>Replacement Date</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="row in history">
                                <td>{{ row.name }}</td>
                                <td>{{ row.phone }}</td>
                                <td>{{ row.start_date }}</td>
                                <td>{{ row.replacement_date }}</td>
                                <td>{{ row.reason }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>
    

@endsection

@section('pageLevelJS')

<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>



@endsection