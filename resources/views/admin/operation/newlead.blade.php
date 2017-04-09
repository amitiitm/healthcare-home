@extends('layouts.admin.master')
<?php
$menuSelected = "leads";
$angularModule = 'adminModule';

?>
@section('title')
    New Lead
@endsection

@section('content')
	<div id="new-lead-view" ng-controller="operationLeadCreateCtrl">

		<div class="page-title">
            <span class="title">New Lead</span>
            <div class="pull-right">
                <button class="btn btn-success" ng-click="addLead()">Save</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Basic Info</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="sub-title required">Customer Name</div>
                        <div class="row">
                            <div class="col-md-6 ">
                                <input type="text" class="form-control" ng-model="leadData.enquiry.name" placeholder="First Name" >
                                <div class="validation-error" ng-show="!leadDataValidation.customerName.valid">
                                    <span ng-bind="leadDataValidation.customerName.message"></span>
                                </div>
                                <div class="margin-bottom-5"></div>

                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" ng-model="leadData.enquiry.lastName" placeholder="Last Name">
								<div class="validation-error" ng-show="!leadDataValidation.customerLastName.valid">
                                    <span ng-bind="leadDataValidation.customerLastName.message"></span>
                                </div>

                            </div>
                        </div>
                        <div class="sub-title required">Customer Email</div>
                        <div>
                            <input type="email" class="form-control" ng-model="leadData.enquiry.email"  placeholder="Customer Email">
                            <div class="validation-error" ng-show="!leadDataValidation.customerEmail.valid">
                                <span ng-bind="leadDataValidation.customerEmail.message"></span>
                            </div>
                        </div>
                        <div class="sub-title required">Customer Phone</div>
                        <div>
                            <input type="phone required" class="form-control" ng-model="leadData.enquiry.phone"  placeholder="Customer Phone" >
                            <div class="validation-error" ng-show="!leadDataValidation.customerPhone.valid">
                                <span ng-bind="leadDataValidation.customerPhone.message"></span>
                            </div>
                        </div>
                        <div class="sub-title required">Service</div>
                        <div>
                            <ui-select ng-model="leadData.service" theme="bootstrap" >
                                <ui-select-match placeholder="Select or type service required">{{$select.selected.name}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.services | filter: $select.search">
                                  <div ng-bind-html="item.name | highlight: $select.search"></div>
                                  <small ng-bind-html="item.email | highlight: $select.search"></small>
                                </ui-select-choices>
                              </ui-select>


                              <div class="validation-error" ng-show="!leadDataValidation.serviceRequired.valid">
                                  <span ng-bind="leadDataValidation.serviceRequired.message"></span>
                              </div>
                        </div>

                        <div class="sub-title">Reference</div>
                        <div>
                            <ui-select ng-model="leadData.reference" theme="bootstrap">
	                            <ui-select-match placeholder="Select Reference">{{$select.selected.label}}</ui-select-match>
	                            <ui-select-choices repeat="item in mappedData.references | filter: $select.search">
	                              <div ng-bind-html="item.label | highlight: $select.search"></div>
	                            </ui-select-choices>
	                          </ui-select>
                        </div>
                        <div ng-show="leadData.reference.id==5">
                            <div class="sub-title">Referral</div>
                            <ui-select ng-model="leadData.reference.child"  theme="bootstrap">
                                <ui-select-match placeholder="Select Referral">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in leadData.reference.children">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div ng-show="leadData.reference.id==6">
                            <div class="sub-title">ECP Model</div>
                            <ui-select ng-model="leadData.reference.child"  theme="bootstrap">
                                <ui-select-match placeholder="Select ECP Model">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in leadData.reference.children">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div ng-show="leadData.reference.id==7">
                            <div class="sub-title">Affinity Partner</div>
                            <ui-select ng-model="leadData.referenceData"  theme="bootstrap">
                                <ui-select-match placeholder="Select Affinity Partner">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in leadData.reference.children">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div ng-show="leadData.reference.child.id==12 || leadData.reference.child.id==21">
                            <div class="sub-title">Doctor</div>
                            <ui-select ng-model="leadData.referenceData"  theme="bootstrap">
                                <ui-select-match placeholder="Select Doctor">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.doctors | filter: $select.search">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div ng-show="leadData.reference.child.id==13 || leadData.reference.child.id==22">
                            <div class="sub-title">Hospital</div>
                            <ui-select ng-model="leadData.referenceData"  theme="bootstrap">
                                <ui-select-match placeholder="Select Hospital">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.hospitals | filter: $select.search">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div ng-show="leadData.reference.child.id==14">
                            <div class="sub-title">Physio</div>
                            <ui-select ng-model="leadData.referenceData"  theme="bootstrap">
                                <ui-select-match placeholder="Select Physio">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.physios | filter: $select.search">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div ng-show="leadData.referenceData==15">
                            <div class="sub-title">Channel Partner</div>
                            <ui-select ng-model="leadData.channel_partner.name"  theme="bootstrap">
                                <ui-select-match placeholder="Select Channel Partner">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.channel_partners | filter: $select.search">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>


                        <div class="sub-title">House No/Address</div>
                        <div>
                            <input type="phone" class="form-control" ng-model="leadData.address" placeholder="House No./Address">
                        </div>
                        <div class="sub-title required">Locality</div>
                        <div>
                            <input placeholder="Enter your locality"  class="form-control" type="text" name="contactName"  g-places-autocomplete options="autocompleteOptions" ng-model="leadData.locality" minlength="3" maxlength="100" >
                            <div class="validation-error" ng-show="!leadDataValidation.localityRequired.valid">
	                              <span ng-bind="leadDataValidation.localityRequired.message"></span>
                            </div>

                        </div>
                        <div class="sub-title required">Landmark</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.landmark" placeholder="Landmark">
                            <div class="validation-error" ng-show="!leadDataValidation.landmarkRequired.valid">
                                <span ng-bind="leadDataValidation.landmarkRequired.message"></span>
                            </div>
                        </div>



                    </div>
                </div>
                <div class="spacer-5"></div>

            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Patient Info</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="sub-title">Name</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.patientInfo.name" placeholder="Patient Name">
                        </div>
                        <div class="sub-title">Gender</div>
                        <div>
                            <ui-select ng-model="leadData.patientInfo.gender" theme="bootstrap">
                             <ui-select-match placeholder="Select or type gender">{{$select.selected.label}}</ui-select-match>
                             <ui-select-choices repeat="item in mappedData.genders | filter: $select.search">
                               <div ng-bind-html="item.label | highlight: $select.search"></div>
                             </ui-select-choices>
                           </ui-select>
                        </div>
                        <div class="sub-title">Age <small>(in years)</small></div>
                        <div>
                            <ui-select ng-model="leadData.patientInfo.age" theme="bootstrap">
                                <ui-select-match placeholder="Patient Age">{{leadData.patientInfo.age}}</ui-select-match>
                                <ui-select-choices repeat="n in range(0,120,1) | filter: $select.search">
                                    {{n}}
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div class="sub-title">Weight <small>(in Kgs)</small></div>
                        <div>
                            <ui-select ng-model="leadData.patientInfo.weight" theme="bootstrap">
                                <ui-select-match placeholder="Patient weight">{{leadData.patientInfo.weight}}</ui-select-match>
                                <ui-select-choices repeat="n in range(1,200,1) | filter: $select.search">
                                    {{n}}
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div class="sub-title">Ailments</div>
                        <div>
                            <ui-select multiple ng-model="leadData.patientInfo.ailment" theme="bootstrap" sortable="true" close-on-select="false" style="width: 100%;">
	                            <ui-select-match placeholder="Select Ailment(s)">{{$item.name}}</ui-select-match>
	                            <ui-select-choices repeat="service in mappedData.ailments  | propsFilter: {name: $select.search}">
	                              <div ng-bind-html="service.name | highlight: $select.search"></div>
	                            </ui-select-choices>
                            </ui-select>
                        </div>
                        <div class="sub-title">Other Ailments</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.patientInfo.otherAilment" placeholder="Other Ailments">
                        </div>
                        <div class="sub-title">Equipment Support</div>
                        <div>
                           <div class="btn-group">
                               <label class="btn btn-success btn-sm" ng-model="leadData.patientInfo.equipmentSupport" uib-btn-radio="true" uncheckable>Yes</label>
                               <label class="btn btn-success btn-sm" ng-model="leadData.patientInfo.equipmentSupport" uib-btn-radio="false" uncheckable>No</label>
                           </div>
                        </div>
                        <div class="sub-title" ng-show="leadData.patientInfo.equipmentSupport">Equipment Detail</div>
                        <div ng-show="leadData.patientInfo.equipmentSupport">
                            <ui-select multiple ng-model="leadData.patientInfo.equipments" theme="bootstrap" sortable="true" close-on-select="false">
	                            <ui-select-match placeholder="Select or type equipment">{{$item.name}}</ui-select-match>
	                            <ui-select-choices repeat="item in mappedData.equipments | filter:  $select.search">
	                              <div ng-bind-html="item.name | highlight: $select.search.name"></div>
	                            </ui-select-choices>
	                          </ui-select>
                        </div>
                        <div class="sub-title">Mobility</div>
                        <div>
                            <ui-select ng-model="leadData.patientInfo.mobility" theme="bootstrap">
                            <ui-select-match placeholder="Select or type patient's mobility">{{$select.selected.label}}</ui-select-match>
                            <ui-select-choices repeat="item in mappedData.mobilities | filter: $select.search">
                              <div ng-bind-html="item.label | highlight: $select.search"></div>
                            </ui-select-choices>
                          </ui-select>
                        </div>
                        <div class="sub-title ng-hide">Patient Illness</div>
                        <div class="ng-hide">
                            <input type="text" class="form-control" placeholder="Patient Illness" ng-model="leadData.patientInfo.illness">
                        </div>
                        <div class="sub-title ng-hide">Physical Condition</div>
                        <div class="ng-hide">
                            <input type="text" class="form-control" placeholder="Physical Condition" ng-model="leadData.patientInfo.physicalCondition">
                        </div>
                        <div class="sub-title">Remark</div>
                        <div>
                            <textarea cols="3" class="form-control" ng-model="leadData.request.remark" placeholder="Remark"></textarea>
                        </div>
                        <div class="sub-title">Doctor Name</div>
                        <div>
                            <input type="text" class="form-control" placeholder="Doctor Name" ng-model="leadData.patientInfo.doctorName">
                        </div>
                        <div class="sub-title">Hospital Name</div>
                        <div>
                            <input type="text" class="form-control" placeholder="Hospital Name" ng-model="leadData.patientInfo.hospitalName">
                        </div>

                        <div class="sub-title">Religion Preference</div>
                        <div>
                            <div class="btn-group">
                                <label class="btn btn-success btn-sm" ng-model="leadData.request.religion" uib-btn-radio="true" uncheckable>Yes</label>
                                <label class="btn btn-success btn-sm" ng-model="leadData.request.religion" uib-btn-radio="false" uncheckable>No</label>
                            </div>
                        </div>
                        <div ng-show="leadData.request.religion" class="sub-title">Religion Preferred</div>
                        <div ng-show="leadData.request.religion">
                            <ui-select ng-model="leadData.request.religionRequired" theme="bootstrap">
                                <ui-select-match placeholder="Select or type equipment">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.religions | filter: $select.search">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div class="sub-title">Age Preference</div>
                        <div>
                            <div class="btn-group">
                                <label class="btn btn-success btn-sm" ng-model="leadData.request.age" uib-btn-radio="true" uncheckable>Yes</label>
                                <label class="btn btn-success btn-sm" ng-model="leadData.request.age" uib-btn-radio="false" uncheckable>No</label>
                            </div>
                        </div>
                        <div  ng-show="leadData.request.age" class="sub-title">Age Preferred</div>
                        <div  ng-show="leadData.request.age">
                            <ui-select ng-model="leadData.request.ageRequired" theme="bootstrap">
                                <ui-select-match placeholder="Select or type equipment">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.ageRanges | filter: $select.search">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        <div class="sub-title">Food Preference</div>
                        <div>
                            <div class="btn-group">
                                <label class="btn btn-success btn-sm" ng-model="leadData.request.food" uib-btn-radio="true" uncheckable>Yes</label>
                                <label class="btn btn-success btn-sm" ng-model="leadData.request.food" uib-btn-radio="false" uncheckable>No</label>
                            </div>
                        </div>
                        <div ng-show="leadData.request.food" class="sub-title">Food Prefered</div>
                        <div ng-show="leadData.request.food">
                            <ui-select ng-model="leadData.request.foodRequired" theme="bootstrap">
                                <ui-select-match placeholder="Select type of food">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.foodOptions | filter: $select.search">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>

                        <div class="sub-title">Upload Prescription</div>
                        <ul ng-show="leadData.patientInfo.prescriptionList.length>0">
                            <li ng-repeat="prescription in leadData.patientInfo.prescriptionList"><span ng-bind="prescription.fileName"></span></li>
                        </ul>
                        <div upload-dialog model="prescriptionUploadModel" ng-model="uploadedPrescription"></div>
                        <div>
                            <button ng-click="uploadPrescription()" class="btn btn-sm btn-default no-margin margin-top-5">Add Prescription</button>
                        </div>

                    </div>
                </div>

                <div class="spacer-5"></div>


                <div class="card" ng-show="leadData.service.id ==3 ">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Physiotherapy Detail</div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="sub-title">Condition</div>
                        <div>
                            <ui-select ng-model="leadData.physio.condition" theme="bootstrap">
                             <ui-select-match placeholder="Select or type patient condition">{{$select.selected.name}}</ui-select-match>
                             <ui-select-choices repeat="item in mappedData.physioConditions | filter: $select.search">
                               <div ng-bind-html="item.name | highlight: $select.search"></div>
                             </ui-select-choices>
                           </ui-select>
                        </div>
                        <div class="sub-title">Pain Severity</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.physio.painSeverity" placeholder="Pain Severity (from 1-10)">
                        </div>
                        <div class="sub-title">Range of Motion</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.physio.rangeOfMotion" placeholder="Range of Motion">
                        </div>
                        <div class="sub-title">Present Condition</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.physio.presentCondition" placeholder="Present Condition">
                        </div>
                        <div class="sub-title">Modalities </div>
                        <div>
                            <ui-select ng-model="leadData.physio.modality" theme="bootstrap">
                             <ui-select-match placeholder="Select patient's modality">{{$select.selected.label}}</ui-select-match>
                             <ui-select-choices repeat="item in mappedData.modalities | filter: $select.search">
                               <div ng-bind-html="item.label | highlight: $select.search"></div>
                             </ui-select-choices>
                           </ui-select>
                        </div>
                        <div class="sub-title">No. of sessions expected </div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.physio.noOfSessions" placeholder="Number of sessions expected">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Service Detail</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="sub-title">Shift Required</div>
                        <div>
                            <ui-select ng-model="leadData.shift" theme="bootstrap">
                                <ui-select-match placeholder="Select or type shift required">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.shifts | filter: $select.search">
                                  <div ng-bind-html="item.label | highlight: $select.search"></div>
                                  <small ng-bind-html="item.email | highlight: $select.search"></small>
                                </ui-select-choices>
                              </ui-select>
                        </div>
                        <div class="sub-title">Task Required</div>
                        <div>
                            <div>
                                <span ng-repeat="taskCat in leadData.validationData.tasks">
                                    <span ng-repeat="taskItem in taskCat.tasks">
                                        <span ng-show="taskItem.selected" class="badge bg-success" ng-bind="taskItem.label"></span>
                                    </span>
                                </span>
                            </div>
                            <button ng-click="selectTask()" class="btn btn-sm btn-default no-margin margin-top-5">Select Task</button>
                        </div>
                        <div class="ng-hide">
                            <ui-select multiple ng-model="leadData.tasks" theme="bootstrap" sortable="true" close-on-select="false" style="width: 100%;">
                                <ui-select-match placeholder="Select person...">{{$item.label}}</ui-select-match>
                                <ui-select-choices repeat="service in mappedData.tasks  | propsFilter: {label: $select.search}">
                                  <div ng-bind-html="service.label | highlight: $select.search"></div>
                                </ui-select-choices>
                              </ui-select>
                        </div>
                        <div class="sub-title">Other Tasks</div>
                        <div>
                            <input type="phone" class="form-control" ng-model="leadData.taskOther" placeholder="Any other task required">
                        </div>
                        <div class="sub-title">Start Date</div>
                        <div>
                            <input type="date" class="form-control" ng-model="leadData.request.startDate" placeholder="Start Date">
                        </div>
                        <div class="sub-title">Start Time</div>
                        <div class="row">
							<div class="col-md-6">
								<div uib-timepicker ng-model="leadData.request.startDate" ng-change="changed()" hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></div>
							</div>
                        </div>
                        <div class="sub-title">Payment Type</div>
                        <div>
                            <ui-select ng-model="leadData.payment.type" theme="bootstrap">
                            <ui-select-match placeholder="Select or type payment type prefered">{{$select.selected.label}}</ui-select-match>
                            <ui-select-choices repeat="item in mappedData.paymentType | filter: $select.search">
                              <div ng-bind-html="item.label | highlight: $select.search"></div>
                            </ui-select-choices>
                          </ui-select>
                        </div>
                        <div class="sub-title">Payment Period</div>
                        <div>
                            <ui-select ng-model="leadData.payment.period" theme="bootstrap">
                                <ui-select-match placeholder="Select or type payment period prefered">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.paymentPeriod | filter: $select.search">
                                  <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                              </ui-select>
                        </div>
                        <div class="sub-title">Payment Mode</div>
                        <div>

                            <ui-select ng-model="leadData.payment.mode" theme="bootstrap">
                                <ui-select-match placeholder="Select or type payment mode prefered">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.paymentMode | filter: $select.search">
                                  <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                              </ui-select>
                        </div>

                        <div class="sub-title">Price</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.payment.price"  placeholder="Price">
                        </div>

                        <div class="sub-title">Price Unit</div>
                        <div>

                            <ui-select ng-model="leadData.payment.priceunit" theme="bootstrap">
                                <ui-select-match placeholder="Select or type Price unit">{{$select.selected.label}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.priceUnit | filter: $select.search">
                                    <div ng-bind-html="item.label | highlight: $select.search"></div>
                                </ui-select-choices>
                            </ui-select>
                        </div>


                        <div class="spacer-40"></div>
                        <div class="spacer-40"></div>
                        <div class="spacer-40"></div>
                        <div class="spacer-40"></div>
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
            <div ng-repeat="taskCat in validationData.tasks">
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

	<script type="text/ng-template" id="uploadPrescriptionModalTemplate.html">
        <div class="modal-header">
            <h3 class="modal-title">Upload Prescription</h3>
        </div>
        <div class="modal-body">
             <div class="text-center">
                <img src="{{daycare.logoUrl}}" class="img-responsive" />
            </div>
            <div upload-dialog model="prescriptionUploadModel"></div>
            <div class="margin-top-10 text-center">
                <button class="btn btn-sm btn-info" ng-click="changeLogo()">Upload Logo</button>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-click="uploadPic(picFile)">Update</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>


@endsection

@section('pageLevelJS')



<script type="text/javascript" src="<% asset('static/js/services/userServices.js')%>"></script>


<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>

<script type="text/javascript" src="<% asset('static/js/services/vendorServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>
@endsection