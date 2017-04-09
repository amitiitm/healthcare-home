
<?php
$menuSelected = "caregiver";
$angularModule = 'vendorModule';

?>
@extends('layouts.admin.master')
@section('title')
    Caregiver Profile
@endsection

@section('content')
    <script>
        var vendorId = '<% $model['vendorId'] %>';
    </script>

    <div id="vendor-view" ng-cloak ng-controller="VendorViewController">
        <div class="pull-right">
            <a class="btn btn-sm btn-default" ng-click="deleteVendor()" aria-hidden="false">Delete Caregiver</a>
            <a href="<% url("/operation/vendor/edit/".$model['vendorId']) %>" class="btn btn-sm btn-default" aria-hidden="false">Edit Caregiver</a>
            <span class="badge badge-success badge-large bg-success ng-binding" ng-bind="lead.service.name"></span>
        </div>
        <div class="page-title">
            <span class="title" >{{vendorData.name}}</span>
        </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="title">Personal Details </div>
                            </div>
                        </div>
                        <div class="card-body">
                               <div class="row">
                                    <div class="spacer-10"></div>
                                    <div class="col-md-6">
		                                <div class="form-group">
		                                    <label for="name">Name: </label> {{vendorData.name}}
		                                </div>
                                        <div class="form-group">
                                            <label for="employee source">Category: </label> {{vendorData.category.name}}
                                        </div>
                                        <div class="form-group">
                                            <label for="employee source">Employee Source: </label> {{vendorData.source.name || 'NA'}}
                                        </div>
                                        <div class="form-group">
                                            <label for="employee source">Agency: </label> {{vendorData.agency.name || 'NA'}}
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email: </label> {{vendorData.email}}
                                        </div>
		                                <div class="form-group">
		                                    <label for="phone">Phone Number: </label> {{vendorData.phone}}
		                                </div>
                                        <div class="form-group">
                                            <label for="phone">Alternate Number: </label> {{vendorData.alternateNo}}
                                        </div>
		                                <div class="form-group">
		                                    <label for="age">Age: </label> {{vendorData.age}} years
		                                </div>
		                                <div class="form-group">
		                                    <label for="weight">Weight: </label> {{vendorData.weight}} kg
		                                </div>
		                                <div class="form-group">
		                                    <label for="height">Height: </label> {{vendorData.height}} ft
		                                </div>
		                                <div class="form-group">
		                                    <label for="address">Address: </label> {{vendorData.address}}
		                                </div>
                                        <div class="form-group">
                                            <label for="address">Qualification: </label> {{vendorData.qualification.label}}
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Experience: </label> <span ng-bind="vendorData.experience"></span><span ng-show="vendorData.experience"> Years</span><span ng-show="!vendorData.experience">NA</span>
                                        </div>
		                                <div class="form-group">
		                                    <label for="religion" ng-model="VendorDetails.religion" >Religion: </label> {{vendorData.religion.label}}
		                                </div>
                                        <div class="form-group">
                                            <label for="voter" ng-model="VendorDetails.voter" >Voter Id: </label> {{vendorData.voter}}
                                        </div>
                                        <div class="form-group">
                                            <label for="aadhar" ng-model="VendorDetails.aadhar" >Aaddhar Id: </label> {{vendorData.aadhar}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
		                                <div class="form-group" >
		                                    <label for="zone">Locality: </label> {{vendorData.localityFormattedAddress}}
		                                </div>
		                                <div class="form-group" >
		                                    <label for="zone">Zone: </label> {{vendorData.zone.label}}
		                                </div>
		                                <div class="form-group">
		                                    <label for="shift">Preferred Shift: </label> {{vendorData.shift.label}}
		                                </div>
                                        <div class="form-group">
                                            <label for="shift">Food Habitat: </label> {{vendorData.food.label}}
                                        </div>
		                                <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="address">Bank Details:</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span ng-show="vendorData.hasBankAccount">Yes</span><span ng-show="!vendorData.hasBankAccount">No</span>
                                                </div>
                                            </div>
                                            <div ng-show="vendorData.bankDetail">
                                            <div class="row" >
                                                <div class="col-sm-6">
                                                    <label for="address">Name</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    {{vendorData.bankDetail.name}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="address">Account No</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    {{vendorData.bankDetail.account_no}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="address">Bank Name</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    {{vendorData.bankDetail.bank_name}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="address">Bank Ifsc Code</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    {{vendorData.bankDetail.ifsc}}
                                                </div>
                                            </div>
                                        </div>
		                                </div>
		                                <div class="form-group">
		                                    <div class="row">
		                                        <div class="col-sm-6">
		                                            <label for="address">Smart Phone User</label>
		                                        </div>
		                                        <div class="col-sm-6">
		                                            <span ng-show="vendorData.hasBankAccount">Yes</span><span ng-show="!vendorData.hasBankAccount">No</span>
		                                        </div>
		                                    </div>
		                                </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="address">Gender</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    {{vendorData.gender.label}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="address">Can work for male patient</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span ng-show="vendorData.worksForMale">Yes</span><span ng-show="!vendorData.worksForMale">No</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="address">Task Required</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span ng-repeat="task in vendorData.task" class="badge task bg-warning color" ng-bind="task.label"></span>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card">
	                    <div class="card-header">
	                        <div class="card-title">
	                            <div class="title">
	                                <div class="pull-left">Availability</div>
									<div class="pull-right">

									</div>
	                            </div>

	                        </div>
	                    </div>
	                    <div class="card-body">
	                        <div class="row">
	                            <div class="col-md-12">
	                                <strong>Available: </strong>
	                                <span ng-show="vendorData.availability.available==1">Yes</span>
	                                <span ng-show="vendorData.availability.available==0">No</span>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-12">
	                                <strong>Detail: </strong>
	                                <span ng-bind="vendorData.availability.availabilityOption.label"></span>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-12">
	                                <strong>Reason: </strong>
	                                <span ng-bind="vendorData.availability.availabilityReason.label"></span>
	                            </div>
	                        </div>
	                        <div class="row" ng-show="vendorData.availability.availableZone.label">
	                            <div class="col-md-12">
	                                <strong>Change on Zone: </strong>
	                                <span ng-bind="vendorData.availability.availableZone.label"></span>

	                            </div>
	                        </div>
	                        <div class="row" ng-show="vendorData.availability.availableShift.label">
	                            <div class="col-md-12">
	                                <strong>Change on Shift: </strong>
	                                <span ng-bind="vendorData.availability.availableShift.label"></span>
	                            </div>
	                        </div>
	                        <div class="row" ng-show="vendorData.availability.availableOtherReason">
	                            <div class="col-md-12">
	                                <strong>Other Reason: </strong>
	                                <span ng-bind="vendorData.availability.availableOtherReason"></span>

	                            </div>
	                        </div>
	                        <div class="row" ng-show="vendorData.availability.availableDate">
	                            <div class="col-md-12">
	                                <strong ng-show="vendorData.availability.available==0">Date till deployed: </strong>
	                                <strong ng-show="vendorData.availability.available==1">Date from Available: </strong>
	                                <span>{{vendorData.availability.availableDate| carbondate}}</span>

	                            </div>
	                        </div>
	                        <div>
								<button class="btn btn-xs btn-success" ng-click="editVendorAvailability()">Edit Availability</button>
                            </div>
	                    </div>
	                </div>

<br />

	                <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="title">
                                    <div class="pull-left">Training Detail</div>
                                    <div class="pull-right">

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="address">Attended Training</label>
                                </div>
                                <div class="col-sm-6">
                                    <span ng-show="vendorData.trainingAttended">Yes</span><span ng-show="!vendorData.trainingAttended">No</span>
                                </div>
                            </div>
                            <div class="row" ng-show="vendorData.trainingAttended">
                                <div class="col-sm-6">
                                    <label for="address">Training Date:</label>
                                </div>
                                <div class="col-sm-6">
                                    <span>{{vendorData.trainingDate | carbondate}}</span>
                                </div>
                            </div>
                            <div class="row" ng-show="!vendorData.trainingAttended">
                                <div class="col-sm-12">
                                    <label for="address">Reason for not attending training:</label>
                                </div>
                                <div class="col-sm-12">
                                    <span ng-bind="vendorData.trainingNotAttendedReason.label"></span>
                                </div>
                            </div>
                            <div class="row" ng-show="!vendorData.trainingAttended && vendorDate.trainingNotAttendedOtherReason!=''">
                                <div class="col-sm-12">
                                    <label for="address">Any other reason for not attending training:</label>
                                </div>
                                <div class="col-sm-12">
                                    <span ng-bind="vendorData.trainingNotAttendedOtherReason"></span>
                                </div>
                            </div>

                        </div>



                    </div>
<br />

	                <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="title">
                                    <div class="pull-left">Documents Uploaded</div>
                                    <div class="pull-right">

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">

							<div class="text-center" ng-show="vendorData.documents.length==0">
								<h4>No Documents Uploaded</h4>
							</div>
							<div ng-show="vendorData.documents.length>0">
								<ul class="list-group">
									<li class="list-group-item" ng-repeat="document in vendorData.documents">
										<a href="{{document.documentUrl}}" target="_blank">
											<span ng-bind="document.caption"></span>
											<span ng-show="document.caption==''">
												<span ng-bind="document.filename"></span>
											</span>
											<strong>(<span ng-bind="document.documentType.label"></span>)</strong>
										</a>
									</li>
								</ul>
							</div>


                        </div>



                    </div>


                </div>

            </div>


        <script type="text/ng-template" id="deleteVendorModalTemplate.html">
            <div class="modal-header">
                <h3 class="modal-title">Delete {{vendorData.name}} </h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="sub-title">Do you realy want to delete this Caregiver?</div>

                    </div>
                </div>
                <div class="col-md-"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success"  type="button" ng-click="deleteVendor()">Yes Delete</button>
                <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
            </div>
        </script>

        </div>

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



<script type="text/javascript" src="<% asset('static/js/services/userServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>

<script type="text/javascript" src="<% asset('static/js/services/vendorServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/vendorscript.js')%>"></script>




@endsection