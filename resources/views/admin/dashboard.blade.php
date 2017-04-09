<?php
$menuSelected = "dashboard";
$angularModule = 'adminModule';
?>
@extends('...layouts.admin.master')

@section('title')
Dashboard
@endsection

@section('content')

<div>
<div id="adminDashboardView" ng-controller="adminDashboardCtrl" ng-cloak>
	<div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="<% url('admin/leads') %>">
                <div class="card green summary-inline">
                    <div class="card-body">
                        <i class="icon fa fa-inbox fa-4x"></i>
                        <div class="content">
                            <div class="title"><span ng-bind="dashboardData.leadCount"></span></div>
                            <div class="sub-title">New Leads</div>
                        </div>
                        <div class="clear-both"></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <div class="card green summary-inline">
                    <div class="card-body">
                        <i class="icon fa fa-comments fa-4x"></i>
                        <div class="content">
                            <div class="title"><span ng-bind="dashboardData.unassignedLeadCount"></span></div>
                            <div class="sub-title">Unassigned Lead</div>
                        </div>
                        <div class="clear-both"></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <div class="card green summary-inline">
                    <div class="card-body">
                        <i class="icon fa fa-tags fa-4x"></i>
                        <div class="content">
                            <div class="title"><span ng-bind="dashboardData.ongoingSessions"></span></div>
                            <div class="sub-title">Ongoing Sessions</div>
                        </div>
                        <div class="clear-both"></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <div class="card green summary-inline">
                    <div class="card-body">
                        <i class="icon fa fa-share-alt fa-4x"></i>
                        <div class="content">
                            <div class="title"><span ng-bind="dashboardData.availableEmployees"></span></div>
                            <div class="sub-title">Available Employees</div>
                        </div>
                        <div class="clear-both"></div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="spacer-10"></div>


    <div class="row">
                <div class="col-md-12">


                       <div role="tabpanel">
                           <!-- Nav tabs -->
                           <ul class="nav nav-tabs" role="tablist">
                               <li role="presentation" ng-class="{'active':tabActive=='forme'}"><a href="#" aria-controls="home" ng-click="openTab('forme')" aria-expanded="true">Assigned To Me <small ng-bind="gridOptionsForMe.data.length" class="badge bg-success"></small></a></li>
                               <li role="presentation" ng-class="{'active':tabActive=='pending'}"><a href="#" aria-controls="profile" ng-click="openTab('pending')"  aria-expanded="false">Pending <small ng-bind="gridOptionsPending.data.length" class="badge bg-success"></small></a></li>
                               <li role="presentation" ng-class="{'active':tabActive=='today'}"><a href="#" aria-controls="messages" ng-click="openTab('today')" aria-expanded="false">Today's Lead <small ng-bind="gridOptionsToday.data.length" class="badge bg-success"></small></a></li>
                               <li role="presentation" ng-class="{'active':tabActive=='all'}"><a href="#" aria-controls="settings" ng-click="openTab('all')" aria-expanded="false">All <small ng-bind="gridOptions.data.length" class="badge bg-success"></small></a></li>
                               <li role="presentation" ng-class="{'active':tabActive=='validated'}"><a href="#" aria-controls="settings" ng-click="openTab('validated')" aria-expanded="false">Validated <small ng-bind="gridOptionsValidated.data.length" class="badge bg-success"></small></a></li>
                               <li role="presentation" ng-class="{'active':tabActive=='activelead'}"><a href="#" aria-controls="settings" ng-click="openTab('activelead')" aria-expanded="false">Active Customer <small ng-bind="gridOptionsActiveLead.data.length" class="badge bg-success"></small></a></li>
                               <li role="presentation" ng-class="{'active':tabActive=='closedlead'}"><a href="#" aria-controls="settings" ng-click="openTab('closedlead')" aria-expanded="false">Closed/Hold <small ng-bind="gridOptionsClosedLead.data.length" class="badge bg-success"></small></a></li>
                           </ul>
                           <!-- Tab panes -->
                           <div class="tab-content">
                               <div role="tabpanel" class="tab-panes" ng-if="tabActive=='forme'">
                                    <div class="table-responsive">
                                       <div id="grid1" ui-grid="gridOptionsForMe" class="grid"></div>
                                   </div>
                               </div>
                               <div role="tabpanel" class="tab-panes" ng-if="tabActive=='pending'">

                                     <div class="table-responsive">
                                        <div id="grid2" ui-grid="gridOptionsPending" class="grid"></div>
                                    </div>
                               </div>
                               <div role="tabpanel" class="tab-panes" ng-if="tabActive=='today'">
                                     <div class="table-responsive">
                                        <div id="grid2" ui-grid="gridOptionsToday" class="grid"></div>
                                    </div>
                               </div>
                               <div role="tabpanel" class="tab-panes" ng-if="tabActive=='all'">
                                     <div class="table-responsive">
                                         <div id="grid1" ui-grid="gridOptions" ui-grid-infinite-scroll ui-grid-selection class="grid"></div>
                                     </div>
                               </div>
                               <div role="tabpanel" class="tab-panes" ng-if="tabActive=='validated'">
                                     <div class="table-responsive">
                                         <div id="grid1" ui-grid="gridOptionsValidated" ui-grid-selection class="grid"></div>
                                     </div>
                               </div>
                               <div role="tabpanel" class="tab-panes" ng-if="tabActive=='activelead'">
                                     <div class="table-responsive">
                                         <div id="grid1" ui-grid="gridOptionsActiveLead" ui-grid-selection class="grid"></div>
                                     </div>
                               </div>
                               <div role="tabpanel" class="tab-panes" ng-if="tabActive=='closedlead'">
                                     <div class="table-responsive">
                                         <div id="grid1" ui-grid="gridOptionsClosedLead"  class="grid"></div>
                                     </div>
                               </div>
                           </div>
                       </div>

					<div>
						<div class="row">
							<div class="col-md-3">
								<i class="fa fa-stop grid-cell-customer-app-not-installed"></i> <span>App not installed</span>
							</div>
							<div class="col-md-3">
								<i class="fa fa-stop bg-lead-no-action-font"></i> <span>No action taken to lead since 10 mins</span>
							</div>
							<div class="col-md-3">
								<i class="fa fa-stop bg-lead-no-employee-font"></i> <span>No employee is assigned to the approved lead</span>
							</div>
							<div class="col-md-3">
								<i class="fa fa-stop bg-lead-no-qc-font"></i> <span>No QC is assigned since 30 mins of lead approval</span>
							</div>
							<div class="col-md-3">
								<i class="fa fa-stop bg-lead-no-cg-font"></i> <span>No CG is assigned since 4 hrs of employee assignment</span>
							</div>
						</div>
					</div>


                </div>
        </div>


        <div class="new-lead-button">

		        <md-button class="md-fab md-primary" ng-show="gridApi.selection.getSelectedRows().length>0" aria-label="Use Android" ng-click="deleteSelectedRow(gridApi.selection.getSelectedRows())">
		          <i class="fa fa-trash"></i>
		        </md-button>
                <md-button class="md-fab md-primary" aria-label="Use Android" ng-click="createServiceLead($event,'','')">
                  <i class="fa fa-plus"></i>
                </md-button>

        </div>
</div>


<script type="text/ng-template" id="bulkDeleteLinkConfirmation.html">
    <div class="modal-header">
        <h3 class="modal-title">Delete Confirmation</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="alert fresh-color alert-{{message.type}}" role="alert" ng-show="message.show">
                    <p ng-bind="message.body"></p>
                </div>
				<p>Are you sure you want to delete <span ng-bind="leadList.length"></span> leads?</p>
            </div>
        </div>
        <div class="col-md-"></div>
    </div>
    <div class="modal-footer">
        <div class="pull-left">
            <input ng-model="checkAll" type="checkbox" /> I Agree
        </div>
         <button class="btn btn-success" type="button" ng-disabled="!checkAll" ng-click="deleteBulkLead()">Yes Delete Lead</button>
         <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
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
</div>

@endsection

@section('pageLevelJS')

<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>


@endsection