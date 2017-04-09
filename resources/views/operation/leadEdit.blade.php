@extends('layouts.admin.master')
<?php
$menuSelected = "leads";
$angularModule = 'adminModule';
?>
@section('title')
    Edit Lead
@endsection

@section('content')
<script>
		var leadId = '<% $model['leadId'] %>';
	</script>
	<div id="new-lead-view" ng-controller="operationLeadValidateCtrl">
		<div class="page-title">
            <span class="title">Edit Lead</span>
            <div class="pull-right">
                <button class="btn btn-default" ng-click="showValidationPitch()">Validation Pitch</button>
                <button class="btn btn-success" ng-click="updateLead()">Update</button>
            </div>
        </div>

        <div class="clearfix clear-both clear"></div>
        <div>
            <div class="alert fresh-color alert-{{message.type}} alert-dismissible" ng-show="message.show" role="alert">
                <span ng-bind="message.body"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Basic Info</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="sub-title required">Customer Name</div>

                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" ng-model="leadData.enquiry.name" placeholder="First Name" >
                                <div class="validation-error" ng-show="!leadDataValidation.customerName.valid">
                                    <span ng-bind="leadDataValidation.customerName.message"></span>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" ng-model="leadData.enquiry.lastName" placeholder="Last Name">
                                <div class="validation-error" ng-show="!leadDataValidation.customerLastName.valid">
                                    <span ng-bind="leadDataValidation.customerLastName.message"></span>
                                </div>

                            </div>

                        </div>
                        <div class="sub-title">Customer Email</div>
                        <div>
                            <input type="email" class="form-control" ng-model="leadData.enquiry.email"  placeholder="Customer Email">
                        </div>
                        <div  ng-show="leadData.enquiry.phone!='xxxxxxxxxx'" class="sub-title required">Customer Phone</div>
                        <div  ng-show="leadData.enquiry.phone!='xxxxxxxxxx'">
                            <input type="phone required" class="form-control" ng-model="leadData.enquiry.phone"  placeholder="Customer Phone" tooltip-class="tooltip-error" uib-tooltip="{{leadDataValidation.customerPhone.message}}" tooltip-is-open="!leadDataValidation.customerPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                        </div>
                        <div class="sub-title required">Service</div>
                        <div>
                            <ui-select ng-model="leadData.service" theme="bootstrap" tooltip-class="tooltip-error" uib-tooltip="{{leadDataValidation.serviceRequired.message}}" tooltip-is-open="!leadDataValidation.serviceRequired.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                                <ui-select-match placeholder="Select or type service required">{{$select.selected.name}}</ui-select-match>
                                <ui-select-choices repeat="item in mappedData.services | filter: $select.search">
                                  <div ng-bind-html="item.name | highlight: $select.search"></div>
                                  <small ng-bind-html="item.email | highlight: $select.search"></small>
                                </ui-select-choices>
                              </ui-select>
                        </div>

                        <div class="sub-title">Reference</div>
                        <div>
                            <ui-select ng-model="leadData.reference"  theme="bootstrap">
	                            <ui-select-match placeholder="Select Reference">{{$select.selected.label}}</ui-select-match>
	                            <ui-select-choices repeat="item in mappedData.references | filter: $select.search">
	                              <div ng-bind-html="item.label | highlight: $select.search"></div>
	                            </ui-select-choices>
	                          </ui-select>
                        </div>

                        <div class="sub-title">House No/Address</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.address" placeholder="House No./Address">
                        </div>
                        <div class="sub-title required">Locality</div>
                        <div>
                            <input placeholder="Enter your locality"  class="form-control" type="text" name="contactName"  g-places-autocomplete options="autocompleteOptions" ng-model="leadData.locality" minlength="3" maxlength="100" tooltip-class="tooltip-error" uib-tooltip="{{leadDataValidation.localityRequired.message}}" tooltip-is-open="!leadDataValidation.localityRequired.valid" tooltip-trigger="none" tooltip-placement="bottom-left">

                        </div>
                        <div class="sub-title">Landmark</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.landmark" placeholder="Land mark">
                        </div>



                    </div>
                </div>
                <div class="spacer-5"></div>

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

                        <div class="sub-title">Other Tasks</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.taskOther" placeholder="Any other task required">
                        </div>
                        <div class="sub-title">Start Date</div>
                        <div>
                            <input type="date" class="form-control" ng-model="leadData.request.startDate" placeholder="Start Date">
                        </div>
                        <div class="sub-title">Start Time</div>
                        <div>
                            <div uib-timepicker ng-model="leadData.request.startDate" ng-change="changed()" hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></div>
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

                            <ui-select ng-model="leadData.payment.priceUnit" theme="bootstrap">
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
            <div class="col-sm-4">
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
                            <input type="text" class="form-control" ng-model="leadData.patientInfo.age" placeholder="Patient Age">
                        </div>
                        <div class="sub-title">Weight <small>(in Kgs)</small></div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.patientInfo.weight" placeholder="Patient Weight">
                        </div>
                        <div class="sub-title">Ailments</div>
                        <div>
                            <ui-select multiple ng-model="leadData.patientInfo.ailments" theme="bootstrap" sortable="true" close-on-select="false" style="width: 100%;">
	                            <ui-select-match placeholder="Select ailment...">{{$item.name}}</ui-select-match>
	                            <ui-select-choices repeat="service in mappedData.ailments  | propsFilter: {name: $select.search, age: $select.search}">
	                              <div ng-bind-html="service.name | highlight: $select.search"></div>
	                            </ui-select-choices>
	                          </ui-select>
                        </div>
                        <div class="sub-title">Other Ailments</div>
                        <div>
                            <input type="text" class="form-control" ng-model="leadData.patientInfo.otherAilment" placeholder="Other Ailment">
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
	                            <ui-select-choices repeat="item in mappedData.equipments | filter: $select.search">
	                              <div ng-bind-html="item.name | highlight: $select.search"></div>
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

                    </div>
                </div>

                <div class="spacer-5"></div>



            </div>
        </div>
		<div class="spacer-5"></div>
		<div class="row">
			<div class="col-md-6">
				<div class="card">
	                <div class="card-header">
	                    <div class="card-title">
	                        <div class="title">Patient Care Plan</div>
	                    </div>
	                </div>
	                <div class="card-body">
	                    <div class="sub-title">Morning Wakeup Time</div>
	                    <div>
	                        <div uib-timepicker ng-model="leadData.patientInfo.morningWakeupTime" ng-change="changed()" hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></div>

	                    </div>
	                    <div class="sub-title">Morning Breakfast Time</div>
	                    <div>
	                        <div uib-timepicker ng-model="leadData.patientInfo.morningBreakfastTime" ng-change="changed()" hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></div>
						</div>
	                    <div class="sub-title">Medicine</div>
	                    <div>
	                        <input type="text" class="form-control" ng-model="leadData.patientInfo.medicine" placeholder="Patient Medicines">
	                    </div>
	                    <div class="sub-title">Lunch Timing</div>
	                    <div>
	                        <div uib-timepicker ng-model="leadData.patientInfo.lunchTime" ng-change="changed()" hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></div>
                        </div>
	                    <div class="sub-title">Dinner Timing</div>
	                    <div>
	                        <div uib-timepicker ng-model="leadData.patientInfo.dinnerTime" ng-change="changed()" hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></div>
                        </div>
	                    <div class="sub-title">Walking Time</div>
	                    <div>
	                        <div uib-timepicker ng-model="leadData.patientInfo.walkingTime" ng-change="changed()" hour-step="hstep" minute-step="mstep" show-meridian="ismeridian"></div>
						</div>
	                    <div class="sub-title ng-hide">Walking Location</div>
	                    <div class="ng-hide">
	                        <input type="text" class="form-control" ng-model="leadData.patientInfo.walkingLocation" placeholder="Patient Walking Location">
	                    </div>
	                    <div class="sub-title ng-hide">Gender Preference</div>
	                    <div class="ng-hide">
	                        <div class="btn-group">
	                            <label class="btn btn-success btn-sm" ng-model="leadData.request.gender" uib-btn-radio="true" uncheckable>Yes</label>
	                            <label class="btn btn-success btn-sm" ng-model="leadData.request.gender" uib-btn-radio="false" uncheckable>No</label>
	                        </div>
	                    </div>
	                    <div class="sub-title ng-hide" ng-show="leadData.request.gender">Gender Preferred</div>

	                    <div class="ng-hide" ng-show="leadData.request.gender">
	                         <ui-select ng-model="leadData.request.genderRequired" theme="bootstrap">
	                             <ui-select-match placeholder="Select or type gender">{{$select.selected.label}}</ui-select-match>
	                             <ui-select-choices repeat="item in mappedData.genders | filter: $select.search">
	                               <div ng-bind-html="item.label | highlight: $select.search"></div>
	                             </ui-select-choices>
	                           </ui-select>
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
	                    <div class="sub-title ng-hide">Language Preference</div>
	                    <div class="ng-hide">
	                        <div class="btn-group">
	                            <label class="btn btn-success btn-sm" ng-model="leadData.request.language" uib-btn-radio="true" uncheckable>Yes</label>
	                            <label class="btn btn-success btn-sm" ng-model="leadData.request.language" uib-btn-radio="false" uncheckable>No</label>
	                        </div>
	                    </div>
	                    <div cl ng-show="leadData.request.language" class="sub-title ng-hide">Language Preferred</div>
	                    <div class="ng-hide" ng-show="leadData.request.language">
	                        <ui-select ng-model="leadData.request.languageRequired" theme="bootstrap">
	                        <ui-select-match placeholder="Select or type equipment">{{$select.selected.label}}</ui-select-match>
	                        <ui-select-choices repeat="item in mappedData.languages | filter: $select.search">
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
                        <div style="height: 100px;"></div>
	                </div>
	            </div>
			</div>
			<div class="col-md-6">
				<div class="card">
	                <div class="card-header">
	                    <div class="card-title">
	                        <div class="title">Task Required</div>
	                    </div>
	                </div>
	                <div class="card-body">
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
	            </div>
			</div>
		</div>
	</div>
	<script type="text/ng-template" id="validationPitch.html">
        <div class="modal-header">
            <h3 class="modal-title">Validation Pitch</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">


                    <p>Hello, Is that Mr. / Ms. _____Mam/Sir, Good Morning/Good Afternoon/Good Evening.</p>
                    <p>This	is	Ms. Divya / Mr. Sachin from Pramaticare.</p>
                    <p>You have requested for an	attendant for	your patient. Can I take your 5 minutes. </p>
                    <p>Mam/Sir, I	am	from	sourcing	department	and	I	have	called	you	for	validation	and	reconfirmation	about
the	details	of your patient. Can I ask	you few	details	in	respect	to	your patient so	that I can	provide	you with the appropriate attandant.</p>
                    <p>Male/female</p>
                    <p>Shift-</p>
                    <p>Height</p>
                    <p>Weight</p>
                    <p>Patient Illness</p>
                    <p>Physical Condition</p>
                    <p>Mobility</p>
                    <p>Basic Care</p>
                    <p>Client Expectation</p>
                    <p>Reporting Time</p>
                    <p>Daily routine</p>
                    <p>a)Morning Wakeup Time</p>
                    <p>b) Morning Breakfast</p>
                    <p>c)Medicine</p>
                    <p>d)Lunch</p>
                    <p>e)Dinner</p>
                    <p>f) Which Time walk</p>
                    <p>g)Location</p>
                    <p>h) Care Giver  Veg /Non-Veg /religion</p>
                    <h4>PLACEMENT DONE TIME:</h4>
                    <p> Thank you mam/sir, I will revert back to you with the confirmation of the attendant 2 hr/1 day before the placement time/ reporting time.</p><p> Henceforth, I will be in touch with you hence pls save my number for future interactions.</p>


                </div>
            </div>
            <div class="col-md-"></div>
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