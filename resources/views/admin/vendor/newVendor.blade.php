<?php
$menuSelected = "vendors";
$angularModule = 'vendorModule';
?>

@extends('layouts.admin.master')

@section('title')
New Caregiver
@endsection

@section('content')
	<script>

	</script>
	<div id="new-lead-view" ng-controller="VendorAddController">

            <div class="page-title">
                <span class="title">Add New Caregiver</span>
                <div class="pull-right">
                    <button class="btn btn-success" ng-click="addVendor()">Save</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="title">Basic Info</div>
                            </div>
                        </div>
                        <div>
                            <div class="alert fresh-color alert-{{message.type}} alert-dismissible" ng-show="message.show" role="alert">
                                <span ng-bind="message.body"></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                            <div class="col-md-4">
                                <div class="sub-title required">Category</div>
                                <div>

                                    <ui-select ng-model="vendorData.category"  theme="bootstrap" >
                                        <ui-select-match placeholder="Select category">{{$select.selected.name}}</ui-select-match>
                                        <ui-select-choices repeat="item in mappedData.services | filter: $select.search">
                                            <div ng-bind-html="item.name | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                    <div class="validation-error" ng-show="!vendorDataValidation.category.valid">
                                        <span ng-bind="vendorDataValidation.category.message"></span>
                                    </div>
                                </div>


                                <div class="sub-title required">Employee Source</div>
                                <div>
                                    <ui-select ng-model="vendorData.source"  theme="bootstrap" >
                                        <ui-select-match placeholder="Select Employee Source">{{$select.selected.name}}</ui-select-match>
                                        <ui-select-choices repeat="item in mappedData.vendorSources | filter: $select.search">
                                            <div ng-bind-html="item.name | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                    <div class="validation-error" ng-show="!vendorDataValidation.source.valid">
                                        <span ng-bind="vendorDataValidation.source.message"></span>
                                    </div>
                                </div>
                                <div class="sub-title" ng-if="vendorData.source.name == 'Agency'">Agency</div>
                                <div class="sub-titles" ng-if="vendorData.source.name == 'Agency'">
                                    <ui-select ng-model="vendorData.agency"  theme="bootstrap">
                                        <ui-select-match placeholder="Select Agency">{{$select.selected.name}}</ui-select-match>
                                        <ui-select-choices repeat="item in mappedData.agencies | filter: $select.search">
                                            <div ng-bind-html="item.name | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                    <div class="validation-error" ng-show="!vendorDataValidation.agency.valid">
                                        <span ng-bind="vendorDataValidation.agency.message"></span>
                                    </div>
                                </div>
                                <div class="sub-title required">Name</div>
                                <div>
                                    <input type="text" class="form-control" ng-model="vendorData.name" placeholder="Name">

                                    <div class="validation-error" ng-show="!vendorDataValidation.name.valid">
                                        <span ng-bind="vendorDataValidation.name.message"></span>
                                    </div>
                                </div>
                                <div class="sub-title">Email</div>
                                <div>
                                    <input type="email" class="form-control" ng-model="vendorData.email"  placeholder="Email">
                                </div>
                                <div class="sub-title required">Phone Number</div>
                                <div>
                                    <input type="text" class="form-control" ng-model="vendorData.phone"  placeholder="Phone Number" >

                                    <div class="validation-error" ng-show="!vendorDataValidation.phone.valid">
                                        <span ng-bind="vendorDataValidation.phone.message"></span>
                                    </div>
                                </div>
                                <div class="sub-title">Alternate Number</div>
                                <div>
                                    <input type="text" class="form-control" ng-model="vendorData.alternate_no"  placeholder="Alternate Number">
                                </div>

                                <!-- <div class="sub-title required">Address</div>
                                <div>
                                    <input type="text" class="form-control" ng-model="vendorData.address"  placeholder="Address" >
                                    <div class="validation-error" ng-show="!vendorDataValidation.address.valid">
                                        <span ng-bind="vendorDataValidation.address.message"></span>
                                    </div>
                                </div>-->


                                <div class="sub-title required">Locality</div>
                                <div>
                                <input placeholder="Enter your locality"  class="form-control" type="text" name="contactName"  g-places-autocomplete options="autocompleteOptions" ng-model="vendorData.locality" minlength="3" maxlength="100" tooltip-class="tooltip-error" uib-tooltip="{{leadDataValidation.localityRequired.message}}" tooltip-is-open="!leadDataValidation.localityRequired.valid" tooltip-trigger="none" tooltip-placement="bottom-left">

                                    <div class="validation-error" ng-show="!vendorDataValidation.locality.valid">
                                        <span ng-bind="vendorDataValidation.locality.message"></span>
                                    </div>
                                </div>

                                <div class="sub-title required">Location Of Work</div>
                                <div>
                                    <ui-select ng-model="vendorData.zone"  theme="bootstrap" >
                                        <ui-select-match placeholder="Select location of work">{{$select.selected.label}}</ui-select-match>
                                        <ui-select-choices repeat="item in mappedData.zones | filter: $select.search">
                                            <div ng-bind-html="item.label | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                    <div class="validation-error" ng-show="!vendorDataValidation.zone.valid">
                                        <span ng-bind="vendorDataValidation.zone.message"></span>
                                    </div>
                                </div>
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />

                            </div>

                            <div class="col-md-4">
                                <div class="sub-title required">Gender</div>
                                <div>
                                    <ui-select ng-model="vendorData.gender"  theme="bootstrap">
                                        <ui-select-match placeholder="Select gender">{{$select.selected.label}}</ui-select-match>
                                        <ui-select-choices repeat="item in mappedData.genders | filter: $select.search">
                                            <div ng-bind-html="item.label | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>

                                    <div class="validation-error" ng-show="!vendorDataValidation.gender.valid">
                                        <span ng-bind="vendorDataValidation.gender.message"></span>
                                    </div>
                                </div>
                                <div class="sub-title required" ng-show="vendorData.gender.slug == 'F'">Is she ready to work for male patient</div>
                                <div ng-show="vendorData.gender.slug == 'F'">
                                    <div class="btn-group">
                                        <label class="btn btn-success btn-sm" ng-model="vendorData.work_for_male" uib-btn-radio="true" uncheckable>Yes</label>
                                        <label class="btn btn-success btn-sm" ng-model="vendorData.work_for_male" uib-btn-radio="false" uncheckable>No</label>
                                    </div>
                                </div>
                                <div class="sub-title required">Age <small>(in years)</small></div>
                                <div>
                                    <ui-select ng-model="vendorData.age" theme="bootstrap">
                                        <ui-select-match placeholder="Age">{{vendorData.age}}</ui-select-match>
                                        <ui-select-choices repeat="n in range(0,120,1) | filter: $select.search">
                                            {{n}}
                                        </ui-select-choices>
                                    </ui-select>
                                    <div class="validation-error" ng-show="!vendorDataValidation.age.valid">
                                        <span ng-bind="vendorDataValidation.age.message"></span>
                                    </div>
                                </div>
                                <div class="sub-title required">Weight <small>(in kgs)</small></div>
                                <div>
                                    <ui-select ng-model="vendorData.weight" theme="bootstrap">
                                        <ui-select-match placeholder="Weight">{{vendorData.weight}}</ui-select-match>
                                        <ui-select-choices repeat="n in range(1,200,1) | filter: $select.search">
                                            {{n}}
                                        </ui-select-choices>
                                    </ui-select>
                                    <div class="validation-error" ng-show="!vendorDataValidation.weight.valid">
                                        <span ng-bind="vendorDataValidation.weight.message"></span>
                                    </div>
                                </div>
                                <div class="sub-title required">Height <small>(in cms)</small></div>
                                <div>
                                    <input type="number" class="form-control" ng-model="vendorData.height" min="0"  placeholder="Height">
                                       <div class="validation-error" ng-show="!vendorDataValidation.height.valid">
                                            <span ng-bind="vendorDataValidation.height.message"></span>
                                        </div>
                                </div>
                                <div class="sub-title required">Religion</div>
                                <div>
                                    <ui-select ng-model="vendorData.religion"  theme="bootstrap" >
                                        <ui-select-match placeholder="Select Religion">{{$select.selected.label}}</ui-select-match>
                                        <ui-select-choices repeat="item in mappedData.religions | filter: $select.search">
                                            <div ng-bind-html="item.label | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                    <div class="validation-error" ng-show="!vendorDataValidation.religion.valid">
                                        <span ng-bind="vendorDataValidation.religion.message"></span>
                                    </div>
                                </div>
                                <div class="sub-title">Food Habit</div>
                                <div>
                                    <ui-select ng-model="vendorData.food"  theme="bootstrap">
                                        <ui-select-match placeholder="Food Preference">{{$select.selected.label}}</ui-select-match>
                                        <ui-select-choices repeat="item in mappedData.foodOptions | filter: $select.search">
                                            <div ng-bind-html="item.label | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                </div>

                                <div class="sub-title">Qualifications</div>
                                <div>
                                    <ui-select ng-model="vendorData.qualification"  theme="bootstrap">
                                        <ui-select-match placeholder="Select qualification">{{$select.selected.label}}</ui-select-match>
                                        <ui-select-choices repeat="item in mappedData.qualifications | filter: $select.search">
                                            <div ng-bind-html="item.label | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                </div>
                                <div class="sub-title">Experience(in years)</div>
                                <div>
                                  <ui-select ng-model="vendorData.experience" theme="bootstrap">
                                        <ui-select-match placeholder="Experience">{{vendorData.experience}}</ui-select-match>
                                        <ui-select-choices repeat="n in range(0,80,1) | filter: $select.search">
                                            {{n}}
                                        </ui-select-choices>
                                    </ui-select>
                                   <!-- <input type="text" class="form-control" ng-model="vendorData.experience"  placeholder="Experience" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.x}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left"> -->

                                </div>
                                <div class="sub-title required">Preferred Shift</div>
                                <div>
                                    <ui-select ng-model="vendorData.preferred_shift"  theme="bootstrap">
                                        <ui-select-match placeholder="Select preferred shift">{{$select.selected.label}}</ui-select-match>
                                        <ui-select-choices repeat="item in mappedData.shifts | filter: $select.search">
                                            <div ng-bind-html="item.label | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>

                                    <div class="validation-error" ng-show="!vendorDataValidation.shift.valid">
                                        <span ng-bind="vendorDataValidation.shift.message"></span>
                                    </div>
                                </div>




                            </div>

                            <div class="col-md-4">
                                <div class="sub-title required">Task Performed</div>
                                <div>
                                    <div>
                                        <span ng-repeat="taskCat in vendorData.validationData">
                                            <span ng-repeat="taskItem in taskCat.tasks">
                                                <span ng-show="taskItem.selected" class="badge bg-success" ng-bind="taskItem.label"></span>
                                            </span>
                                        </span>
                                    </div>
                                    <button ng-click="selectTask()" class="btn btn-sm btn-default no-margin margin-top-5">Select Task</button>
                                    <div class="validation-error" ng-show="!vendorDataValidation.task.valid">
                                        <span ng-bind="vendorDataValidation.task.message"></span>
                                    </div>
                                </div>




                                <div class="sub-title required">Smart Phone</div>
                                <div>
                                    <div class="btn-group" tooltip-class="tooltip-error" >
                                        <label class="btn btn-success btn-sm" ng-model="vendorData.has_smart_phone" uib-btn-radio="true" uncheckable>Yes</label>
                                        <label class="btn btn-success btn-sm" ng-model="vendorData.has_smart_phone" uib-btn-radio="false" uncheckable>No</label>
                                    </div>
                                    <div class="validation-error" ng-show="!vendorDataValidation.havingSmartPhone.valid">
	                                    <span ng-bind="vendorDataValidation.havingSmartPhone.message"></span>
	                                </div>
                                </div>
                                <div class="sub-title required">Has bank Account</div>
                                <div>
                                    <div class="btn-group">
                                        <label class="btn btn-success btn-sm" ng-model="vendorData.has_bank_account" uib-btn-radio="true" uncheckable>Yes</label>
                                        <label class="btn btn-success btn-sm" ng-model="vendorData.has_bank_account" uib-btn-radio="false" uncheckable>No</label>
                                    </div>
                                    <div class="validation-error" ng-show="!vendorDataValidation.hasBankAccount.valid">
	                                    <span ng-bind="vendorDataValidation.hasBankAccount.message"></span>
	                                </div>
                                </div>
                                <div ng-show="vendorData.has_bank_account">
                                    <div class="sub-title required">Account Holder Name</div>
                                    <div>
                                        <input type="text" class="form-control" ng-model="vendorData.bank_account.name"  placeholder="Account Holder Name" >
                                        <div class="validation-error" ng-show="!vendorDataValidation.hasBankAccountHolderName.valid">
                                            <span ng-bind="vendorDataValidation.hasBankAccountHolderName.message"></span>
                                        </div>
                                    </div>

                                    <div class="sub-title required">Account Number</div>
                                    <div>
                                        <input type="text" class="form-control" ng-model="vendorData.bank_account.accountNo"  placeholder="Bank Account Number" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.x}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                                        <div class="validation-error" ng-show="!vendorDataValidation.hasBankAccountNumber.valid">
                                            <span ng-bind="vendorDataValidation.hasBankAccountNumber.message"></span>
                                        </div>
                                    </div>
                                    <div class="sub-title required">Bank Name</div>
                                    <div>
                                        <input type="text" class="form-control" ng-model="vendorData.bank_account.bankName"  placeholder="Bank name" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.x}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                                        <div class="validation-error" ng-show="!vendorDataValidation.hasBankName.valid">
                                            <span ng-bind="vendorDataValidation.hasBankName.message"></span>
                                        </div>
                                    </div>
                                    <div class="sub-title required">IFSC Code</div>
                                    <div>

                                        <input type="text" class="form-control" ng-model="vendorData.bank_account.ifsc"  placeholder="Bank IFSC Code" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.x}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                                        <div class="validation-error" ng-show="!vendorDataValidation.hasBankIFSCCode.valid">
                                            <span ng-bind="vendorDataValidation.hasBankIFSCCode.message"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="sub-title required">Attended Training</div>
                                <div>
                                    <div class="btn-group">
                                        <label class="btn btn-success btn-sm" ng-model="vendorData.training_attended" uib-btn-radio="1" uncheckable>Yes</label>
                                        <label class="btn btn-success btn-sm" ng-model="vendorData.training_attended" uib-btn-radio="0" uncheckable>No</label>
                                    </div>

                                    <div class="validation-error" ng-show="!vendorDataValidation.trainingAttended.valid">
	                                    <span ng-bind="vendorDataValidation.trainingAttended.message"></span>
	                                </div>
                                </div>

                                <div ng-show="vendorData.training_attended==1">
                                    <div class="sub-title">Date of Training</div>
                                    <div>
                                        <input type="date" name="training_attended_date" ng-model="vendorData.training_date" />
                                    </div>
                                </div>
                                <div ng-show="vendorData.training_attended==0">
                                    <div class="sub-title">Reason why training is not attended?</div>
                                    <div>
                                        <ui-select ng-model="vendorData.training_not_attended_reason" theme="bootstrap" >
                                            <ui-select-match placeholder="Select Reason">{{$select.selected.label}}</ui-select-match>
                                            <ui-select-choices repeat="item in trainingNotAttendedReason | filter: $select.search">
                                                <div ng-bind-html="item.label | highlight: $select.search"></div>
                                            </ui-select-choices>
                                        </ui-select>
                                        <br />
                                        <div>
                                            <input type="text" class="" ng-model="vendorData.training_not_attended_other_reason" placeholder="Other Reason" />
                                        </div>
                                    </div>

                                </div>

                                <hr />

                                <div class="sub-title">Voter Id</div>
                                <div>
                                    <input type="text" class="form-control" ng-model="vendorData.voter" min="12" max="12" placeholder="Voter Id" >

                                    <div class="validation-error" ng-show="!vendorDataValidation.voter.valid">
                                        <span ng-bind="vendorDataValidation.voter.message"></span>
                                    </div>
                                </div>

                                <div class="sub-title">Aadhar Id</div>
                                <div>
                                    <input type="text" class="form-control" ng-model="vendorData.aadhar"  placeholder="Aadhar Id" >

                                    <div class="validation-error" ng-show="!vendorDataValidation.aadhar.valid">
                                        <span ng-bind="vendorDataValidation.aadhar.message"></span>
                                    </div>
                                </div>

<hr />
                                <div class="sub-title">Upload Documents</div>
                                <ul class="list-unstyled">
                                    <li ng-repeat="document in vendorDocuments"><input type="checkbox" ng-model="document.selected" /> <span ng-bind="document.document.caption"></span> (<span ng-bind="document.document.documentType.label"></span>)</li>
                                </ul>
                                <div upload-dialog model="prescriptionUploadModel" ng-model="uploadedPrescription"></div>
                                <div>
                                    <button class="btn btn-success btn-default" ng-click="uploadDocument()">Upload Document</button>
                                </div>


                            </div>
                            </div>
                            <br /><br /><br /><br /><br /><br />
                        </div>
                    </div>

                </div>


            </div>
        </div>


	<script type="text/ng-template" id="leadTaskModalTemplate.html">
        <div class="modal-header">
            <h3 class="modal-title">Select Task</h3>
        </div>
        <div class="modal-body">
            <div ng-repeat="taskCat in validationData">
                <strong class="title" ng-bind="taskCat.label"></strong>
                <div class="row">
                    <div class="col-md-6" ng-repeat="task in taskCat.tasks">
                        <div>
                            <input type="checkbox" id="checkbox-task-{{task-id}}"  ng-model="task.selected">
                            <label for="checkbox-task-{{task-id}}">
                              <span ng-bind="task.label"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-click="updateTask()">Update</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>
	<script type="text/ng-template" id="vendorDocumentModalTemplate.html">
        <div class="modal-header">
            <h3 class="modal-title">Upload Vendor Document</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Select Document Type</label>
                        <ui-select ng-model="vendorDocument.type" theme="bootstrap" >
                            <ui-select-match placeholder="Select Document Type">{{$select.selected.label}}</ui-select-match>
                            <ui-select-choices repeat="item in documentTypeList | filter: $select.search">
                                <div ng-bind-html="item.label | highlight: $select.search"></div>
                            </ui-select-choices>
                        </ui-select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Select Document Type</label>
                        <input type="text" class="form-control" ng-model="vendorDocument.caption" placeholder="File Caption" />
                    </div>
                </div>
            </div>
            <div class="align-center">
                <img ng-src="{{inputfile.resized.dataURL}}" />
            </div>
            <div class="align-center margin-auto">
                <input id="inputImage"  type="file" name="inputImage " accept="image/*"
                       image="inputfile" resize-max-height="300" class="margin-auto" resize-max-width="250"
                       resize-quality="0.7" />
            </div>

            <uib-progressbar ng-show="updating" class="progress-striped active" value="dynamic" type="info">Updating...</uib-progressbar>

        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-click="uploadfile()">Update</button>
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