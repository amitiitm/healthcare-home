
@extends('layouts.front.master')

@section('title')
For Specialized Patient Care at Home in Delhi NCR
@endsection

@section('content')
<div ng-controller="welcomeCtrl">
	<section id="slider_part">
     <div class="carousel slide" id="carousel-example-generic" data-ride="carousel">
        <!-- Indicators -->
         <!--<ol class="carousel-indicators text-center">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
         </ol>-->
        <div class="carousel-inner">
            <div class="item active">
                <div class="overlay-slide">
                    <img src="<% asset('static/images/banner/pramati-home.png') %>" alt="" class="img-responsive">
                </div>
                <h1 class="main-heading hidden-md hidden-lg">Health Care at home</h1>
                <div class="carousel-caption">
                    <div class="container">
                        <div class="hero-content text-center">
	                        <div class="hero-text wow fadeIn" data-wow-delay=".8s">
	                            <div class="row">
	                                <div class="col-md-6">
	                                    <div class="service-board">
	                                        <div>
                                               <div class="panel-default" ng-show="status.open=='start'">
                                                   <h1 class="old-h1">Choose Your Service</h1>
                                                   <div class="spacer-20 hidden-xs"></div>


                                                   <div class="row">
                                                       <div class="col-sm-3 col-xs-6 service-item" ng-click="selectService(2)">
                                                           <div class="service-circle">
                                                               <div class="contain">
                                                                   <i class="pr icon-assistive-3"></i>
                                                               </div>
                                                           </div>
                                                           <div class="service-name">Assistive</div>
                                                       </div>
                                                       <div class="col-sm-3 col-xs-6 service-item" ng-click="selectService(1)">
                                                           <div class="service-circle">
                                                               <div class="contain">
                                                                   <i class="pr icon-nursing"></i>
                                                               </div>
                                                           </div>
                                                           <div class="service-name">Nursing</div>
                                                       </div>
                                                       <div class="col-sm-3 col-xs-6 service-item" ng-click="selectService(3)">
                                                           <div class="service-circle">
                                                               <div class="contain">
                                                                   <i class="pr icon-physiotherapist"></i>
                                                               </div>
                                                           </div>
                                                           <div class="service-name">Physiotherapy</div>
                                                       </div>
                                                       <div class="col-sm-3 col-xs-6 service-item" ng-click="selectService(6)">
                                                           <div class="service-circle">
                                                               <div class="contain">
                                                                   <i class="pr icon-speech-therapist-3"></i>
                                                               </div>
                                                           </div>
                                                           <div class="service-name">Speech/ Occupational</div>
                                                       </div>
                                                       <div class="col-sm-3 col-xs-6 service-item" ng-click="selectService(7)">
                                                           <div class="service-circle">
                                                               <div class="contain">
                                                                   <i class="pr icon-dr-icon"></i>
                                                               </div>
                                                           </div>
                                                           <div class="service-name">Doctor</div>
                                                       </div>
                                                       <div class="col-sm-3 col-xs-6 service-item" ng-click="selectService(4)">
                                                           <div class="service-circle">
                                                               <div class="contain">
                                                                   <i class="pr icon-delivery-boy-2"></i>
                                                               </div>
                                                           </div>
                                                           <div class="service-name">Medicine</div>
                                                       </div>
                                                       <div class="col-sm-3 col-xs-6 service-item" ng-click="selectService(5)">
                                                           <div class="service-circle">
                                                               <div class="contain">
                                                                   <i class="pr icon-daignostic"></i>
                                                               </div>
                                                           </div>
                                                           <div class="service-name">Diagnostics</div>
                                                       </div>
                                                       <div class="col-sm-3 col-xs-6 service-item" ng-click="selectService(8)">
                                                           <div class="service-circle">
                                                               <div class="contain">
                                                                   <i class="pr icon-stethoschope"></i>
                                                               </div>
                                                           </div>
                                                           <div class="service-name">Equipments</div>
                                                       </div>
                                                   </div>
                                              </div>
                                              <div class="back-link-floating" ng-show="status.open=='customer-detail'">
                                                  <button class="btn btn-sm btn-link" ng-click="backToHome()"><i class="fa fa-angle-left"></i> Back</button>
                                              </div>
                                                <div class="panel-default ng-hide" ng-show="status.open=='customer-detail'">

                                                    <h1 class="old-h1">Tell us more about yourself</h1>
                                                    <div class="spacer-5"></div>

                                                    <div class="spacer-5"></div>
                                                    <div class="form-group customer-detail">

                                                        <div class="row">
                                                            <div class="col-sm-4"><div class="text-control">Name: </div></div><div class="col-sm-8"><input class="form-control" ng-change="validateCustomerInfoForm(customerFormSubmitted)" ng-model="leadData.customerInfo.name" type="text" placeholder="Enter Your Name" name="name" tooltip-class="tooltip-error" uib-tooltip="{{leadDataValidation.customerName.message}}" tooltip-is-open="!leadDataValidation.customerName.valid" tooltip-trigger="none" tooltip-placement="bottom-left" ></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group customer-detail">
                                                        <div class="row">
                                                            <div class="col-sm-4"><div class="text-control">Email: </div></div><div class="col-sm-8"><input class="form-control" ng-change="validateCustomerInfoForm(customerFormSubmitted)" ng-model="leadData.customerInfo.email" type="text" placeholder="Enter Your Email" name="email" ></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group customer-detail">
                                                        <div class="row">
                                                            <div class="col-sm-4"><div class="text-control">Phone: </div></div><div class="col-sm-8"><input class="form-control" ng-change="validateCustomerInfoForm(customerFormSubmitted)" ng-model="leadData.customerInfo.phone" type="text" placeholder="Enter Your Number" name="phone" tooltip-class="tooltip-error" uib-tooltip="{{leadDataValidation.customerPhone.message}}" tooltip-is-open="!leadDataValidation.customerPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left"></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group customer-detail">
                                                        <div class="row">
                                                            <div class="col-sm-4"><div class="text-control">Locality: </div></div><div class="col-sm-8"><input class="form-control" ng-change="validateCustomerInfoForm(customerFormSubmitted)" type="text" placeholder="Enter Your Locality" name="locality"  g-places-autocomplete options="autocompleteOptions" ng-model="leadData.locality" minlength="3" maxlength="100" tooltip-class="tooltip-error" uib-tooltip="{{leadDataValidation.localityRequired.message}}" tooltip-is-open="!leadDataValidation.localityRequired.valid" tooltip-trigger="none" tooltip-placement="bottom-left" ></div>
                                                        </div>
                                                        </div>
                                                    <div class="form-group customer-detail">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="text-left" ><button class="btn btn-xs btn-link no-padding back-link" ng-click="backToHome()"><i class="fa fa-chevron-left "></i> Back</button></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <small class="light-font"></small>

                                                        </div>
                                                    </div>

                                                    <div class=" footer-panel">

                                                        <div class="row">
                                                            <div class="col-md-6 col-xs-6"><button ng-click="callMeNow()" class="btn btn-success pull-left btn-front-wizard" name="call"><i class="fa fa-phone"></i> Call Me Now</button></div>
                                                            <div class="col-md-6 col-xs-6"><button ng-click="submitCustomerDetail()" class="btn btn-success pull-rights btn-front-wizard" name="myself"><i class="fa fa-rocket"></i> Continue</button></div>
                                                        </div>

                                                    </div>
                                              </div>
                                                <div class="panel-default ng-hide" ng-show="status.open=='medicine-info'">

                                                    <h1 class="old-h1">Tell us more about yourself</h1>
                                                    <div class="spacer-5"></div>
                                                    <div class="form-group customer-detail">
                                                        <div class="row">
                                                            <div class="col-sm-4"><div class="text-control">Name: </div></div><div class="col-sm-8"><input class="form-control" ng-change="validateCustomerInfoForm(customerFormSubmitted)" ng-model="leadData.customerInfo.name" type="text" placeholder="Enter Your Name" name="name" tooltip-class="tooltip-error" uib-tooltip="{{leadDataValidation.customerName.message}}" tooltip-is-open="!leadDataValidation.customerName.valid" tooltip-trigger="none" tooltip-placement="bottom-left" ></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group customer-detail">
                                                        <div class="row">
                                                            <div class="col-sm-4"><div class="text-control">Email: </div></div><div class="col-sm-8"><input class="form-control" ng-change="validateCustomerInfoForm(customerFormSubmitted)" ng-model="leadData.customerInfo.email" type="text" placeholder="Enter Your Email" name="email" ></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group customer-detail">
                                                        <div class="row">
                                                            <div class="col-sm-4"><div class="text-control">Phone: </div></div><div class="col-sm-8"><input class="form-control" ng-change="validateCustomerInfoForm(customerFormSubmitted)" ng-model="leadData.customerInfo.phone" type="text" placeholder="Enter Your Number" name="phone" tooltip-class="tooltip-error" uib-tooltip="{{leadDataValidation.customerPhone.message}}" tooltip-is-open="!leadDataValidation.customerPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left"></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group customer-detail">
                                                        <div class="row">
                                                            <div class="col-sm-4"><div class="text-control">Locality: </div></div><div class="col-sm-8"><input class="form-control" ng-change="validateCustomerInfoForm(customerFormSubmitted)" type="text" placeholder="Enter Your Locality" name="locality"  g-places-autocomplete options="autocompleteOptions" ng-model="leadData.locality" minlength="3" maxlength="100" tooltip-class="tooltip-error" uib-tooltip="{{leadDataValidation.localityRequired.message}}" tooltip-is-open="!leadDataValidation.localityRequired.valid" tooltip-trigger="none" tooltip-placement="bottom-left" ></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group customer-detail">
                                                        <div class="row">
                                                            <div class="col-sm-4"><div class="text-control">Prescription: </div></div><div class="col-sm-8">
                                                                <div class="form-group customer-detail">
                                                                    <ul>
                                                                        <li ng-repeat="prescription in leadData.patientInfo.prescriptionList"><span ng-bind="prescription.fileName"></span></li>
                                                                    </ul>
                                                                    <div upload-dialog model="prescriptionUploadModel" ng-model="uploadedPrescription"></div>
                                                                    <div>
                                                                        <div>
                                <span ng-repeat="taskCat in leadData.validationData.tasks">
                                    <span ng-repeat="taskItem in taskCat.tasks">
                                        <span ng-show="taskItem.selected" class="badge bg-success" ng-bind="taskItem.label"></span>
                                    </span>
                                </span>
                                                                        </div>
                                                                        <button ng-click="uploadPrescription()" class="btn btn-sm btn-default no-margin margin-top-5">Upload Prescription</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-left" ><button class="btn btn-xs btn-link no-padding back-link" ng-click="backToHome()" style="margin-top: 0"><i class="fa fa-chevron-left "></i> Back</button></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <small class="light-font"></small>

                                                        </div>
                                                    </div>

                                                    <div class=" footer-panel">

                                                        <div class="row">
                                                            <div class="col-md-6 col-xs-6"><button ng-click="callMeNow()" class="btn btn-success pull-left btn-front-wizard" name="call"><i class="fa fa-phone"></i> Call Me Now</button></div>
                                                            <div class="col-md-6 col-xs-6"><button ng-click="submitCustomerDetail()" class="btn btn-success pull-right btn-front-wizard" name="myself"><i class="fa fa-rocket"></i> Submit Form</button></div>
                                                        </div>

                                                    </div>
                                              </div>
                                                <div class="panel-default ng-hide" ng-show="status.open=='call-success'">
	                                                <h1 class="old-h1">Thank you for submitting your requirement. </h1>
	                                                <h2 class="subtitle-text">We have received your requirement successfully! Our team will get in touch with you soon.</h2>
	                                                <div class=" footer-panel">
	                                                      <div class="row">
	                                                          <div class="col-xs-12 text-center"><button ng-click="backToHome(true)" class="btn btn-success btn-front-wizard" name="call">Back</button></div>
	                                                      </div>

	                                                </div>
	                                           </div>

	                                           <div class="panel-default ng-hide" ng-show="status.open=='otp-screen'">
                                                    <h1 class="old-h1">Thank you for submitting your requirement. </h1>
                                                    <h2 class="subtitle-text">Please enter OTP send to your phone</h2>
                                                     <div class="row">
	                                                      <div class="col-xs-12 text-center"><input type="text" placeholder="OTP Received" ng-model="otp" /></div>
	                                                 </div>
	                                                 <div class="spacer-5"></div>
	                                                 <div class="row">
	                                                      <div class="col-xs-12 text-center"><button ng-click="verifyOtp()" ng-disabled="!otp" class="btn btn-sm btn-success btn-front-wizard" name="call">Verify OTP and Call</button></div>
	                                                  </div>
                                                      <div class=" footer-panel">
                                                          <div class="row">
                                                              <div class="col-xs-12 text-center"><button ng-click="selectService(leadData.serviceId)" class="btn btn-success btn-front-wizard" name="call">Edit Phone Number</button></div>
                                                          </div>
													  </div>
                                               </div>

                                               <div class="panel-default ng-hide" ng-show="status.open=='call-screen'">
                                                   <h1 class="old-h1">Thank you for verifying phone number </h1>
                                                   <h2 class="subtitle-text">One of our executive will call you</h2>
													<div class="text-center">

														<i class="fa fa-phone fa-4x call-icon-big fa-pulse"></i>
													</div>

                                                     <div class=" footer-panel">
                                                         <div class="row">
                                                             <div class="col-xs-12 text-center"><button ng-click="backToHome(true)" class="btn btn-success btn-front-wizard" name="call">Back To Home</button></div>
                                                         </div>
                                                  </div>
                                              </div>
	                                           <div class="panel-default ng-hide" ng-show="status.open=='lead-success'">
	                                                <h1 class="old-h1">Thank you for submitting your requirement. </h1>
	                                                <h2 class="subtitle-text">We have received your requirement successfully! Our team will get in touch with you soon.</h2>
	                                                <div class=" footer-panel">
	                                                      <div class="row">
	                                                          <div class="col-xs-12 text-center"><button ng-click="backToHome(true)" class="btn btn-success btn-front-wizard" name="call">Back</button></div>
	                                                      </div>

	                                                </div>
	                                           </div>
	                                            <div class="panel-default ng-hide" ng-show="status.open=='patient-info'">
                                                   <div class="Patient-block">
                                                            <h1 class="old-h1">Patient Information</h1>
                                                           <div class="spacer-20"></div>
                                                       <div class="form-group">
                                                           <div class="row">
                                                               <div class="col-sm-4"><div class="text-control">Name</div></div>
                                                               <div class="col-sm-8"><input class="form-control" ng-change="validatePatientInfoForm(patientInfoSubmitted)" ng-model="leadData.patientInfo.name" type="text" placeholder="Enter patient Name" name="name" tooltip-class="tooltip-error"  tooltip-is-open="!patientInfoValidation.name.valid" tooltip-trigger="none" tooltip-placement="bottom-left" ></div>
                                                           </div>
                                                       </div>
                                                               <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="text-control">Gender</div>
                                                                    </div>
                                                                    <div class="col-sm-8">
                                                                        <div class="btn-gsroup btn-toggle align" tooltip-class="tooltip-error" uib-tooltip="{{patientInfoValidation.gender.message}}" tooltip-is-open="!patientInfoValidation.gender.valid" tooltip-trigger="none" tooltip-placement="bottom-left" >
                                                                            <label class="btn btn-success btn-grey" ng-click="validatePatientInfoForm(patientInfoSubmitted)" ng-model="leadData.patientInfo.gender" uib-btn-radio="1" >Male</label>
                                                                            <label class="btn btn-success btn-grey" ng-click="validatePatientInfoForm(patientInfoSubmitted)" ng-model="leadData.patientInfo.gender" uib-btn-radio="2" >Female</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-4"><div class="text-control">Age</div></div>
                                                                   <div class="col-sm-8"><input class="form-control" ng-change="validatePatientInfoForm(patientInfoSubmitted)" ng-model="leadData.patientInfo.age" type="number" placeholder="Enter patient Age in Years" name="age" tooltip-class="tooltip-error" uib-tooltip="{{patientInfoValidation.age.message}}" tooltip-is-open="!patientInfoValidation.age.valid" tooltip-trigger="none" tooltip-placement="bottom-left" ></div>
                                                               </div>
                                                           </div>
                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-4">
                                                                       <div class="text-control">Weight</div>
                                                                   </div>
                                                                   <div class="col-sm-8"><input class="form-control" ng-change="validatePatientInfoForm(patientInfoSubmitted)" ng-model="leadData.patientInfo.weight" type="number" placeholder="Enter patient Weight in Kgs" name="weight" tooltip-class="tooltip-error" uib-tooltip="{{patientInfoValidation.weight.message}}" tooltip-is-open="!patientInfoValidation.weight.valid" tooltip-trigger="none" tooltip-placement="bottom-left" ></div>
                                                               </div>
                                                           </div>
                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-4">
                                                                       <div class="text-control">Ailments</div>
                                                                   </div>
                                                                   <div class="col-sm-8">
                                                                        <div class="row">
                                                                           <div class="col-sm-6" ng-repeat="ailment in ailments | limitTo: 3">
                                                                               <div class="ailment-item" ng-click="validatePatientInfoForm(patientInfoSubmitted)">
                                                                                   <input type="checkbox" id="checkbox-fa-light-1" ng-model="ailment.selected">
                                                                                   <label for="checkbox-fa-light-1">
                                                                                     <span ng-bind="ailment.name"></span>
                                                                                   </label>
                                                                               </div>
                                                                           </div>
                                                                           <div class="col-sm-6">
				                                                              <div class="ailment-item">
				                                                                  <input type="checkbox" id="checkbox-fa-light-1" ng-model="leadData.patientInfo.ailmentOther">
				                                                                  <label for="checkbox-fa-light-1">
				                                                                    <span>Other</span>
				                                                                  </label>
				                                                              </div>
				                                                          </div>
                                                                       </div>

                                                                   </div>
                                                               </div>
                                                           </div>
                                                           <div class="footer-panel">
                                                               <div class="row">
                                                                   <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-left" type="button" ng-click="gotoCustomerInformation()">Back</button></div></div>
                                                                   <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-right" type="button" ng-click="submitPatientInfo()">Continue</button></div></div>
                                                               </div>
                                                           </div>
                                                       </div>
	                                           </div>
	                                           <div class="panel-default ng-hide" ng-show="status.open=='patient-info-physio'">
                                                   <div class="patient-physio-block">
                                                            <h1 class="old-h1">Patient Information</h1>
                                                           <div class="spacer-20"></div>

                                                               <div class="form-group">
                                                                   <div class="row">
                                                                       <div class="col-sm-5"><div class="text-control">Name</div></div>
                                                                       <div class="col-sm-7"><input class="form-control" ng-change="validatePhysioPatientInfoForm(physioPatientInfoSubmitted)" ng-model="leadData.physioPatientInfo.name" type="name" placeholder="Enter patient Name" name="name" tooltip-class="tooltip-error" uib-tooltip="{{physioPatientInfoValidation.name.message}}" tooltip-is-open="!physioPatientInfoValidation.age.valid" tooltip-trigger="none" tooltip-placement="bottom-left" ></div>
                                                                   </div>
                                                               </div>
                                                               <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-sm-5">
                                                                        <div class="text-control">Gender</div>
                                                                    </div>
                                                                    <div class="col-sm-7">
                                                                        <div class="btn-gsroup btn-toggle align"  tooltip-class="tooltip-error" uib-tooltip="{{physioPatientInfoValidation.gender.message}}" tooltip-is-open="!physioPatientInfoValidation.gender.valid" tooltip-trigger="none" tooltip-placement="bottom-left" >
                                                                            <label class="btn btn-success btn-grey" ng-click="validatePhysioPatientInfoForm(physioPatientInfoSubmitted)"  ng-model="leadData.physioPatientInfo.gender" uib-btn-radio="'M'" >Male</label>
                                                                            <label class="btn btn-success btn-grey" ng-click="validatePhysioPatientInfoForm(physioPatientInfoSubmitted)" ng-model="leadData.physioPatientInfo.gender" uib-btn-radio="'F'" >Female</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-5"><div class="text-control">Age</div></div>
                                                                   <div class="col-sm-7"><input class="form-control" ng-change="validatePhysioPatientInfoForm(physioPatientInfoSubmitted)" ng-model="leadData.physioPatientInfo.age" type="number" placeholder="Enter patient Age in Years" name="age" tooltip-class="tooltip-error" uib-tooltip="{{physioPatientInfoValidation.age.message}}" tooltip-is-open="!physioPatientInfoValidation.age.valid" tooltip-trigger="none" tooltip-placement="bottom-left" ></div>
                                                               </div>
                                                           </div>
                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-5">
                                                                       <div class="text-control">Weight</div>
                                                                   </div>
                                                                   <div class="col-sm-7"><input class="form-control" ng-change="validatePhysioPatientInfoForm(physioPatientInfoSubmitted)" ng-model="leadData.physioPatientInfo.weight" type="number" placeholder="Enter patient Weight in Kgs" name="weight" tooltip-class="tooltip-error" uib-tooltip="{{physioPatientInfoValidation.weight.message}}" tooltip-is-open="!physioPatientInfoValidation.weight.valid" tooltip-trigger="none" tooltip-placement="bottom-left" ></div>
                                                               </div>
                                                           </div>
                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-5">
                                                                       <div class="text-control">Condition</div>
                                                                   </div>
                                                                   <div class="col-sm-7">
																		<select ng-change="validatePhysioForm()" name="repeatSelect" class="physio-condition-selector" ng-model="leadData.physioPatientInfo.condition"  tooltip-class="tooltip-error" uib-tooltip="{{specialReqValidation.gender.message}}" tooltip-is-open="!specialReqValidation.gender.valid" tooltip-trigger="none" tooltip-placement="bottom-left" >
																			<option selected disabled value="">Select Condition</option>
		                                                                    <option ng-repeat="option in mappedData.physioConditions" value="{{option.id}}">{{option.name}}</option>
		                                                                </select>
                                                                   </div>
                                                               </div>

                                                           </div>
                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-5">
                                                                       <div class="text-control">Present Condition</div>
                                                                   </div>
                                                                   <div class="col-sm-7">
                                                                        <input class="form-control"  ng-model="leadData.physioPatientInfo.presentCondition" type="text" placeholder="Present condition of patient" name="preset-condition"  >
                                                                   </div>

                                                               </div>

                                                           </div>

                                                        <div class="row">
                                                            <div class="col-md-12"><small class="light-font"></small></div>
                                                        </div>
                                                           <div class="footer-panel">
                                                               <div class="row">
                                                                   <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-left" type="button" ng-click="gotoCustomerInformation()">Back</button></div></div>
                                                                   <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-right" type="button" ng-click="submitPhysioPatientInfo()">Continue</button></div></div>
                                                               </div>
                                                           </div>
                                                       </div>
	                                           </div>


	                                           <div class="panel-default ng-hide" ng-show="status.open=='service-requirement'">
                                                  <div class="Patient-block">
                                                       <h1 class="old-h1">Task Required</h1>
                                                       <div class="spacer-20"></div>
													   <div class="form-group">
                                                           <div class="row">
                                                               <div class="col-sm-4">
                                                                   <div class="text-control">Shift Detail</div>
                                                               </div>
                                                               <div class="col-sm-8">
                                                                   <div class="btn-grsoup btn-toggle shift-selector align" tooltip-class="tooltip-error" uib-tooltip="{{patientInfoValidation.shift.message}}" tooltip-is-open="!patientInfoValidation.shift.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                                                                       <label class="btn btn-success btn-grey" ng-click="serviceTaskFormValidation(serviceTaskFormSubmitted)" ng-model="leadData.requirements.shift" uib-btn-radio="1" >12 hrs Day</label>
                                                                       <label class="btn btn-success btn-grey" ng-click="serviceTaskFormValidation(serviceTaskFormSubmitted)" ng-model="leadData.requirements.shift" uib-btn-radio="2" >12 hrs Night</label>
                                                                       <label class="btn btn-success btn-grey" ng-click="serviceTaskFormValidation(serviceTaskFormSubmitted)" ng-model="leadData.requirements.shift" uib-btn-radio="3" >24 hrs</label>
                                                                   </div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                       <div class="form-group">
                                                           <div class="row">
                                                               <div class="col-sm-4">
                                                                   <div class="text-control">Task</div>
                                                               </div>
                                                               <div class="col-sm-8">
                                                                   <div class="row">
                                                                       <div class="col-md-12" ng-repeat="task in tasks | limitTo: 7">
                                                                           <div class="task-item">
                                                                               <input type="checkbox" id="checkbox-fa-light-1" ng-model="task.selected">
                                                                               <label for="checkbox-fa-light-1">
                                                                                   <span ng-bind="task.label"></span>
                                                                               </label>
                                                                           </div>
                                                                       </div>
                                                                       <div class="col-md-12">
                                                                           <div class="task-item">
                                                                               <input type="checkbox" id="checkbox-fa-light-1" ng-model="leadData.otherTask">
                                                                               <label for="checkbox-fa-light-1">
                                                                                   <span>Other</span>
                                                                               </label>
                                                                           </div>
                                                                       </div>
                                                                   </div>
                                                               </div>
                                                           </div>
                                                       </div>




	                                                   <div class="row">
	                                                       <div class="col-md-12"><small class="light-font"></small></div>
	                                                   </div>
                                                      <div class="footer-panel">
                                                          <div class="row">
                                                              <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-left" type="button" ng-click="fillDetail()">Back</button></div></div>
                                                              <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-right" type="button" ng-click="submitServiceTask()">Continue</button></div></div>
                                                          </div>
                                                      </div>
                                                  </div>
                                               </div>

												<div class="panel-default ng-hide" ng-show="status.open=='service-request'">
                                                      <div class="request-block">
                                                           <h1 class="old-h1">Special Request</h1>
                                                           <div class="spacer-20"></div>
                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-5">
                                                                       <div class="text-control">Religion Preference</div>
                                                                   </div>
                                                                   <div class="col-sm-7">
                                                                       <div class="btn-grsoup btn-toggle shift-selector align pull-left">
                                                                           <label class="btn btn-success btn-grey" ng-model="leadData.request.religion" uib-btn-radio="true" >Yes</label>
                                                                           <label class="btn btn-success btn-grey" ng-model="leadData.request.religion" uib-btn-radio="false" >No</label>
                                                                       </div>
                                                                        <select ng-change="validateSpecialRequest()" name="repeatSelect" ng-show="leadData.request.religion" class=" special-request-choice" ng-model="leadData.request.religionPreferred" tooltip-class="tooltip-error" uib-tooltip="{{specialReqValidation.religion.message}}" tooltip-is-open="!specialReqValidation.religion.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                                                                          <option selected disabled value="">Select Religion</option>
                                                                          <option ng-repeat="option in mappedData.religions" value="{{option.id}}">{{option.label}}</option>
                                                                        </select>
                                                                   </div>
                                                               </div>
                                                           </div>
                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-5">
                                                                       <div class="text-control">Gender Preference</div>
                                                                   </div>
                                                                   <div class="col-sm-7">
                                                                       <div class="btn-grsoup btn-toggle shift-selector align pull-left">
                                                                           <label class="btn btn-success btn-grey" ng-model="leadData.request.gender" uib-btn-radio="true" >Yes</label>
                                                                           <label class="btn btn-success btn-grey" ng-model="leadData.request.gender" uib-btn-radio="false" >No</label>
                                                                       </div>
                                                                       <select ng-change="validateSpecialRequest()" name="repeatSelect" ng-show="leadData.request.gender" class="special-request-choice" ng-model="leadData.request.genderPreferred"  tooltip-class="tooltip-error" uib-tooltip="{{specialReqValidation.gender.message}}" tooltip-is-open="!specialReqValidation.gender.valid" tooltip-trigger="none" tooltip-placement="bottom-left" >
	                                                                     <option selected disabled value="">Select Gender</option>
	                                                                     <option ng-repeat="option in mappedData.genders" value="{{option.id}}">{{option.label}}</option>
	                                                                   </select>
                                                                   </div>
                                                               </div>
                                                           </div>
                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-5">
                                                                       <div class="text-control">Food Preference</div>
                                                                   </div>
                                                                   <div class="col-sm-7">
                                                                       <div class="btn-grsoup btn-toggle shift-selector align pull-left">
                                                                           <label class="btn btn-success btn-grey" ng-model="leadData.request.food" uib-btn-radio="true" >Yes</label>
                                                                           <label class="btn btn-success btn-grey" ng-model="leadData.request.food" uib-btn-radio="false" >No</label>
                                                                       </div>
                                                                       <select ng-change="validateSpecialRequest()" name="repeatSelect" ng-show="leadData.request.food" class="special-request-choice" ng-model="leadData.request.foodPreferred" tooltip-class="tooltip-error" uib-tooltip="{{specialReqValidation.food.message}}" tooltip-is-open="!specialReqValidation.food.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                                                                         <option selected disabled value="">Select Food Type</option>
                                                                         <option ng-repeat="option in mappedData.foodOptions" value="{{option.id}}">{{option.label}}</option>
                                                                       </select>
                                                                   </div>
                                                               </div>
                                                           </div>
                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-5">
                                                                       <div class="text-control">Age</div>
                                                                   </div>
                                                                   <div class="col-sm-7">
                                                                       <div class="btn-grsoup btn-toggle shift-selector align">
                                                                           <label class="btn btn-success btn-grey" ng-model="leadData.request.age" uib-btn-radio="true" >Yes</label>
                                                                           <label class="btn btn-success btn-grey" ng-model="leadData.request.age" uib-btn-radio="false" >No</label>
                                                                       </div>
                                                                       <select ng-change="validateSpecialRequest()" name="repeatSelect" ng-show="leadData.request.age" class="special-request-choice" ng-model="leadData.request.agePreferred" tooltip-class="tooltip-error" uib-tooltip="{{specialReqValidation.age.message}}" tooltip-is-open="!specialReqValidation.age.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                                                                         <option selected disabled value="">Select Age Range</option>
                                                                         <option ng-repeat="option in mappedData.ageRanges" value="{{option.id}}">{{option.label}}</option>
                                                                       </select>
                                                                   </div>
                                                               </div>
                                                           </div>
                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-5">
                                                                       <div class="text-control">Language</div>
                                                                   </div>
                                                                   <div class="col-sm-7">
                                                                       <div class="btn-grsoup btn-toggle shift-selector align pull-left">
                                                                           <label class="btn btn-success btn-grey" ng-model="leadData.request.language" uib-btn-radio="true" >Yes</label>
                                                                           <label class="btn btn-success btn-grey" ng-model="leadData.request.language" uib-btn-radio="false" >No</label>
                                                                       </div>
                                                                       <select ng-change="validateSpecialRequest()" name="repeatSelect" ng-show="leadData.request.language" class="special-request-choice" ng-model="leadData.request.languagePreferred" tooltip-class="tooltip-error" uib-tooltip="{{specialReqValidation.language.message}}" tooltip-is-open="!specialReqValidation.language.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                                                                         <option selected disabled value="">Select Language</option>
                                                                         <option ng-repeat="option in mappedData.languages" value="{{option.id}}">{{option.label}}</option>
                                                                       </select>

                                                                   </div>
                                                               </div>
                                                           </div>

                                                           <div class="form-group">
                                                               <div class="row">
                                                                   <div class="col-sm-5">
                                                                       <div class="text-control">Start Date</div>
                                                                   </div>
                                                                   <div class="col-sm-7">
                                                                       <input type="date" class="pull-left" ng-model="leadData.request.startDate" />
                                                                   </div>
                                                               </div>
                                                           </div>
                                                           <div class="form-group ng-hide">
                                                               <div class="row">
                                                                   <div class="col-sm-6">
                                                                       <div class="text-control">Duration of Service</div>
                                                                   </div>
                                                                   <div class="col-sm-6">
                                                                       <input type="number" ng-model="leadData.request.serviceDuration" placeholder="In no of days" />
                                                                   </div>
                                                               </div>
                                                           </div>



                                                           <div class="row">
                                                               <div class="col-md-12"><small class="light-font"></small></div>
                                                           </div>
                                                          <div class="footer-panel">
                                                              <div class="row">
                                                                  <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-left" type="button" ng-click="gotoServiceRequirement()">Back</button></div></div>
                                                                  <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-right" type="button" ng-click="submitSpecialRequest()">Submit</button></div></div>
                                                              </div>
                                                          </div>
                                                      </div>
                                                   </div>

                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--/ Hero text end -->
                    </div>
                </div>
            </div>
            </div>
         </div>
    </section>
</div>
	    </section>
	    <!--/ Slider end -->

	<!-- Service Area start -->

    <section id="about">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class=" text-center">
						<h3 class="feature_title">How It Works </h3>
					</div>
				</div>  <!-- Col-md-12 End -->
			</div>
            <div class="spacer-20"></div>
			<div class="row">
				<div class="text-center">
					<div class="col-md-4 col-xs-12 col-sm-4">
						<div class="feature_content">
							<i class="pr icon-share"></i>
							<h5>Share</h5>
							<p>Once you have shared details, sit back and relax and we'd get in touch shortly with a suitable resource.</p>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 col-sm-4">
						<div class="feature_content">
							<i class="pr icon-relax"></i>
							<h5>Relax</h5>
							<p>Use our simple Form (above) and tell us what you require, you can even chat with us!</p>
						</div>
					</div>
					<!-- Col-md-4 Single_feature End -->
					<div class="col-md-4 col-xs-12 col-sm-4">
						<div class="feature_content">
							<i class="pr icon-esclate"></i>
							<h5>Escalate</h5>
							<p>Incase we dont revert within 2 hours (during working hours), feel free to leave a message on chat and we'd take this up immediately!</p>
						</div>
					</div> <!-- Col-md-4 Single_feature End -->

					<!-- <button class="btn btn-main"> Read More</button> -->
				</div>
			</div>  <!-- Row End -->
		</div>  <!-- Container End -->
		<!-- Service Area End -->
	</section>

	<section id="popular_area">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class=" text-center">
						<h3 class="feature_title">Popular Care </h3>
					</div>
				</div>  <!-- Col-md-12 End -->
			</div>
            <div class="spacer-20"></div>
			<div class="row">
				<div class="text-center">
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <a href="<% url('/services/assistive-care')%>"><div class="popular_content service-spacer">
                                <div><img src="<% asset('static/images/team/assistive-care.jpg')%>" alt="assistive-care" class="img-responsive"></div>
                                <h4 class="popular-tag">Assistive Care</h4>
                                <span class="light">Let us help with all your caring to-dos!</span>
                            </div></a>
                    </div>
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <a href="<% url('/services/nursing-care')%>"><div class="popular_content service-spacer">
                            <div><img src="<% asset('static/images/team/nurse.jpg')%>" alt="nursing-care" class="img-responsive"></div>
							<h4 class="popular-tag">Nursing Care</h4>
                            <span class="light">Let us help with all your caring to-dos!</span>
						</div></a>
					</div>
					<!-- Col-md-4 Single_feature End -->				</div>
                <div class="text-center">
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <a href="<% url('/services/physiotherapist')%>"><div class="popular_content service-spacer">
                            <div><img src="<% asset('static/images/team/physiotherapist.jpg')%>" alt="physiotherapist" class="img-responsive"></div>
                            <h4 class="popular-tag">Physiotherapist</h4>
                            <span class="light">Let us help with all your caring to-dos!</span>
                        </div></a>
                    </div>
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <a href="<% url('/services/speech-occupational')%>"><div class="popular_content service-spacer">
                                <div><img src="<% asset('static/images/team/occupational.jpg')%>" alt="speech-occupational" class="img-responsive"></div>
                                <h4 class="popular-tag">Speech/Occupational Therapist</h4>
                                <span class="light">Let us help with all your caring to-dos!</span>
                            </div></a>
                    </div>
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <a href="<% url('/services/doctor-visit')%>"><div class="popular_content service-spacer">
                                <div><img src="<% asset('static/images/team/doctor.jpg')%>" alt="doctor-visit" class="img-responsive"></div>
                                <h4 class="popular-tag">Doctor Visit</h4>
                                <span class="light">Let us help with all your caring to-dos!</span>
                            </div></a>
                    </div>
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <a href="<% url('/services/diagnostics-at-home')%>"><div class="popular_content service-spacer">
                                <div><img src="<% asset('static/images/team/diagnostics.jpg')%>" alt="diagnostics-at-home" class="img-responsive"></div>
                                <h4 class="popular-tag">Diagnostics at Home</h4>
                                <span class="light">Let us help with all your caring to-dos!</span>
                            </div></a>
                    </div>
                    <!-- Col-md-4 Single_feature End -->
                    <div class="text-center">
                        <div class="col-md-4 col-xs-12 col-sm-6 col-md-offset-2">
                            <a href="<% url('/services/medicine-delivery')%>"><div class="popular_content service-spacer">
                                    <div><img src="<% asset('static/images/team/medicine.jpg')%>" alt="medicine-delivery" class="img-responsive"></div>
                                    <h4 class="popular-tag">Medicine Delivery</h4>
                                    <span class="light">Let us help with all your caring to-dos!</span>
                                </div></a>
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-6">
                            <a href="<% url('/services/equipments')%>"><div class="popular_content service-spacer">
                                    <div><img src="<% asset('static/images/team/equipment.jpg')%>" alt="equipments" class="img-responsive"></div>
                                    <h4 class="popular-tag">Equipments</h4>
                                    <span class="light">Let us help with all your caring to-dos!</span>
                                </div></a>
                        </div>
            </div>
		</div>
        <div class="spacer-30"></div>
        </div>
        </div><!-- Container End -->
		<!-- Service Area End -->
	</section>

	<section id="partner_area">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class=" text-center">
						<h3 class="feature_title">Featured In </h3>
					</div>
				</div>  <!-- Col-md-12 End -->
			</div>
            <div class="spacer-20"></div>
			<div class="row">
                    <div class="text-center">
                        <div class="col-md-3 col-xs-12 col-sm-6">
                            <div class="feature_content">
                                <img src="<% asset('static/images/banner/techinasia.png')%>" width="100" height="100" alt="techinasia" title="TechInAsia">
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-6">
                            <div class="feature_content">
                                <img src="<% asset('static/images/banner/inc-42.png')%>" width="100" height="100" alt="inc" title="Inc-42">
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-6">
                            <div class="feature_content">
                                <img src="<% asset('static/images/banner/Entrepreneur.png')%>" width="100" height="100" alt="entrepreneur" title="Entrepreneur India">
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-6">
                            <div class="feature_content">
                                <img src="<% asset('static/images/banner/vccircle.png')%>" width="100" height="100" alt="vccircle" title="VCCircle">
                            </div>
                        </div>
                </div>  <!-- Row End -->

				</div>
			</div>  <!-- Row End -->
		</div>  <!-- Container End -->
		<!-- Service Area End -->
	</section>

	<!-- Counter Start -->
	<section id="counter_area"><div class="text-center"><h3 class="new-h1">Our Team </h3></div>
        <div class="spacer-20"></div>
	        <div class="facts">
	            <div class="container">
	                <div class="col-md-3 col-xs-12 col-sm-6 columns">
	                    <div class="facts-wrap">
	                     <div class="graph">
	                        <div class="graph-left-side">
	                            <div class="graph-left-container">
	                                <div class="graph-left-half"> </div>
	                            </div>
	                        </div>
	                        <div class="graph-right-side">
	                            <div class="graph-right-container">
	                                <div class="graph-right-half"></div>
	                            </div>
	                        </div>
	                        <i class="fa fa-heart-o fa-3x fw"></i>
	                        <div class="facts-wrap-num">
	                            <span class="counter"> 500</span>+
	                        </div>
	                    </div>
	                        <h6>Care Givers</h6>
	                    </div>
	                </div>
	                <div class="col-md-3 col-xs-12 col-sm-6 columns">
	                    <div class="facts-wrap">
	                     <div class="graph">
	                        <div class="graph-left-side">
	                            <div class="graph-left-container">
	                                <div class="graph-left-half"> </div>
	                            </div>
	                        </div>
	                        <div class="graph-right-side">
	                            <div class="graph-right-container">
	                                <div class="graph-right-half"></div>
	                            </div>
	                        </div>
	                        <i class="pr icon-nursing fa-3x fw"></i>
	                        <div class="facts-wrap-num"><span class="counter"> 300</span>+
	                        </div>
	                     </div>
	                        <h6>Nurses</h6>
	                    </div>
	                </div>
	                <div class="col-md-3 col-xs-12 col-sm-6 columns">
	                    <div class="facts-wrap">
	                     <div class="graph">
	                        <div class="graph-left-side">
	                            <div class="graph-left-container">
	                                <div class="graph-left-half"> </div>
	                            </div>
	                        </div>
	                        <div class="graph-right-side">
	                            <div class="graph-right-container">
	                                <div class="graph-right-half"></div>
	                            </div>
	                        </div>
	                        <i class="fa fa-check-square-o fa-3x fw"></i>
	                        <div class="facts-wrap-num"><span class="counter"> 3500</span>+
	                        </div>
	                        </div>
	                        <h6>Visits/month</h6>
	                    </div>
	                </div>
	                <div class="col-md-3 col-xs-12 col-sm-6 columns">
	                    <div class="facts-wrap">
	                     <div class="graph">
	                        <div class="graph-left-side">
	                            <div class="graph-left-container">
	                                <div class="graph-left-half"> </div>
	                            </div>
	                        </div>
	                        <div class="graph-right-side">
	                            <div class="graph-right-container">
	                                <div class="graph-right-half"></div>
	                            </div>
	                        </div>
	                        <i class="fa fa-users fa-3x fw"></i>
	                        <div class="facts-wrap-num"><span class="counter"> 1000</span>+
	                        </div>
	                        </div>
	                        <h6>Satisfied Customers</h6>
	                    </div>
	                </div>
	            </div> <!-- Conatainer End -->
	        </div>	<!-- Fact div ENd -->
	</section>
	<!-- Counter End -->


	<!-- Counter End -->

	<section id="testimonial" class="wow fadeInUp">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-12 col-sm-12 col-xs-12">
	                <div class=" text-center">
	                    <h3 class="feature_title">What Our Customers say </h3>
	                    <div class="spacer-40"></div>
	                </div>
	            </div>  <!-- Col-md-12 End -->
	        </div>
	        <div class="row">
                <div class="col-md-6">
                    <div class="row ">

                        <div class="col-md-4 text-center testimonial-pull-right">
                            <img class="img-circle" src="<% asset('static/images/team/rk-khanna.png') %>" alt="testimonial" >
                        </div>
                        <div class="col-md-8 testimonial-pull-left"><div class="testimonial-text1"><b>R.K. Khanna</b></div>
                            <div class="testimonial-text1">I have engaged Caregivers through M/s Pramati Healthcare for rendering round the clock assistance to my wife who is an advanced patient of Alzheimer's disease. When I require some assistance, from M/s Pramati Healthcare, I am attended to promptly and my issues are resolved to my satisfaction.</div>
                        </div>
                    </div>
                    <div class="spacer-20"></div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img class="img-circle" src="<% asset('static/images/team/rummy.png') %>" alt="testimonial" >
                        </div>
                        <div class="col-md-8"><div class="testimonial-text2"><b>Ms. Rummy</b></div>
                            <div class="testimonial-text2">it’s really a good experience with your services. It’s really impressive that you have dress system. Its seems very good when someone visit the patient and found a professional & responsible person is there to take care. Your cg quality is good enough. She comes on time, deliver proper services and grab new things fast.</div>
                        </div>
                    </div>
                </div>
            </div>

	    </div> <!-- Row End -->
	</section> <!-- Section Testimonial End -->
</div>



@endsection