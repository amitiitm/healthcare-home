@extends('...layouts.front.master')

@section('title')
Care Finder
<?php $linkActive = "care-finder"; ?>
@endsection

@section('content')
	<section ng-controller="careFinderCtrl" id="care-finder-page-view">
	    <div class="container">
			<div class="row">
				<div class="col-md-12">
				<form name="careFinderForm">
					<div class="md-tabs-centered-wrapper">
						<md-tabs md-dynamic-height md-stretch-tabs md-border-bottom md-center-tabs md-selected="selectedTab">
                              <md-tab label="Select Service">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <md-content>
	                                        <md-grid-list
	                                                md-cols-gt-md="8" md-cols="2" md-cols-sm="2" md-cols-md="4"
	                                                md-row-height-gt-md="200" md-row-height="150"
	                                                >
	                                                <md-grid-tile class="service-selector-item" ng-repeat="service in staticData.services">

	                                                    <md-radio-group ng-model="careFinderData.service" ng-change="serviceSelected(service)">
	                                                      <md-radio-button ng-value="service" aria-label="{{it.title}}" ng-click="selectServiceClickHandler()">
	                                                          <!--<i ng-class="service.icon_class"></i>-->
	                                                          <div class="service-selector-item-box">
	                                                            <div class="icon-container">
	                                                                <i ng-class="service.icon_class"></i>
	                                                            </div>
	                                                            <div class="label-container">
	                                                                <span ng-bind="service.name"></span>
	                                                            </div>
	                                                          </div>
	                                                          <md-button class="md-fab md-primary ng-hide" aria-label="Use Android">
	                                                            <i ng-class="service.icon_class"></i>
	                                                            <md-tooltip md-direction="top">{{service.name}}</md-tooltip>
	                                                          </md-button>

	                                                      </md-radio-button>
	                                                    </md-radio-group>

	                                                  </md-grid-tile>

	                                        </md-grid-list>

	                                    </md-content>
                                    </div>
                                </div>

                              </md-tab>
                              <md-tab label="Patient Detail">
                                <md-content>
                                    <div class="clear-both spacer-10"></div>
                                    <div>
                                        <div class="row">
                                            <div class="col-md-6 col-md-offset-3">
                                                <md-input-container class="md-block" flex-gt-sm>
                                                    <label>Patient Name</label>
                                                    <input ng-model="careFinderData.patientInfo.name" name="patientName" minlength="3" maxlength="100" required="">
                                                    <div ng-show="careFinderForm.patientName.$invalid && careFinderForm.patientName.$touched" ng-messages="careFinderForm.patientName.$error" role="alert">
                                                        <div ng-message-exp="['required', 'minlength', 'maxlength', 'pattern']">
                                                          Patient name must be between 3 and 100 characters long and look like a name.
                                                        </div>
                                                    </div>
                                                </md-input-container>
                                                <md-input-container class="md-block" flex-gt-sm>
                                                    <label>Genders</label>
                                                    <md-select ng-model="careFinderData.patientInfo.gender" name="patientGender" required md-selected-text="getSelectedText()">
                                                      <md-optgroup label="Gender">
                                                        <md-option ng-value="gender.value" ng-repeat="gender in genders">{{gender.label}}</md-option>
                                                      </md-optgroup>
                                                    </md-select>
                                                    <div ng-show="careFinderForm.patientGender.$invalid && careFinderForm.patientGender.$touched " ng-messages="careFinderForm.patientGender.$error" role="alert">
                                                        <div ng-message-exp="['required']">
                                                          Patient gender must be provided.
                                                        </div>
                                                    </div>
                                                </md-input-container>
                                                <div class="clear-both"></div>
                                                <md-input-container class="md-block" flex-gt-sm>
                                                    <div layout>
                                                    <div flex="10" layout layout-align="center center">
                                                        <span class="md-body-1">Age</span>
                                                    </div>
                                                    <md-slider required flex md-discrete ng-model="careFinderData.patientInfo.age" name="patientAge" step="1" min="1" max="100" aria-label="rating">
                                                    </md-slider>
                                                    <div ng-show="careFinderForm.patientAge.$invalid && careFinderForm.patientAge.$touched " ng-messages="careFinderForm.patientAge.$error" role="alert">
                                                        <div ng-message-exp="['required']">
                                                          Patient age must be provided.
                                                        </div>
                                                    </div>
                                                </div>
                                                    </md-input-container>

                                                <md-input-container class="md-block" flex-gt-sm>
                                                     <div layout>
                                                        <div flex="10" layout layout-align="center center">
                                                            <span class="md-body-1">Weight (in Kgs)</span>
                                                        </div>
                                                        <md-slider required flex md-discrete ng-model="careFinderData.patientInfo.weight" name="patientWeight" step="1" min="1" max="150" aria-label="rating">
                                                        </md-slider>
                                                        <div ng-show="careFinderForm.patientWeight.$invalid && careFinderForm.patientWeight.$touched " ng-messages="careFinderForm.patientWeight.$error" role="alert">
                                                            <div ng-message-exp="['required']">
                                                              Patient age must be provided.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </md-input-container>

                                                <md-input-container>
                                                    <label>Ailments</label>
                                                    <md-select ng-model="careFinderData.patientInfo.ailment"
                                                               md-on-close="clearAilmentSearchTerm()"
                                                               data-md-container-class="selectdemoSelectHeader"
                                                               multiple="" required name="patientAilment">

                                                      <md-optgroup label="Ailments">
                                                        <md-option ng-value="ailment.id" ng-repeat="ailment in staticData.ailments |
                                                          filter:searchTerm">{{ailment.name}}</md-option>
                                                      </md-optgroup>
                                                    </md-select>
                                                    <div ng-show="careFinderForm.patientAilment.$invalid && careFinderForm.patientAilment.$touched " ng-messages="careFinderForm.patientAilment.$error" role="alert">
                                                        <div ng-message-exp="['required']">
                                                          Ailment is required.
                                                        </div>
                                                    </div>
                                                </md-input-container>

                                                <md-input-container class="md-block" flex-gt-sm>
                                                    <md-checkbox ng-model="careFinderData.patientInfo.equipmentSupport" aria-label="Checkbox 1">
                                                        Is patient is on equipment support (optional)
                                                    </md-checkbox>
                                                </md-input-container>

                                                <md-input-container style="margin-right: 10px;" ng-show="careFinderData.patientInfo.equipmentSupport">
                                                    <label>Equipments Details</label>
                                                    <md-select ng-model="careFinderData.patientInfo.equipments">
                                                        <md-option ng-repeat="equipment in staticData.equipments" value="{{equipment.id}}">
                                                            {{equipment.name}}
                                                        </md-option>
                                                    </md-select>
                                                </md-input-container>

												<div class="row">
													<div class="col-xs-6">
														<md-button class="md-raised" ng-click="gotoTab(0)">Previous</md-button>
													</div>
													<div class="col-xs-6">
														<md-button class="md-raised md-primary pull-right" ng-click="gotoTab(2)">Next</md-button>
													</div>
												</div>


                                            </div>
                                        </div>

                                    </div>
                                </md-content>
                              </md-tab>
                              <md-tab label="Requirement">
                                <md-content>

                                        <div class="row">
                                            <div class="col-md-6 col-md-offset-3">
                                                <div class="clear-both spacer-10"></div>
                                                <md-input-container>
                                                    <label>Shift Details:</label>
                                                    <md-select ng-model="careFinderData.shift" name="shiftDetail" required>
                                                        <md-option ng-repeat="shift in staticData.shifts" ng-value="shift.id">
                                                            {{shift.label}}
                                                        </md-option>
                                                    </md-select>
                                                    <div ng-show="careFinderForm.shiftDetail.$invalid && careFinderForm.shiftDetail.$touched " ng-messages="careFinderForm.shiftDetail.$error" role="alert">
                                                        <div ng-message-exp="['required']">
                                                          Shift detail is required.
                                                        </div>
                                                    </div>
                                                </md-input-container>

                                                <md-input-container>
                                                    <label>Task Required</label>
                                                    <md-select ng-model="careFinderData.task"
                                                               data-md-container-class="selectdemoSelectHeader"
                                                               multiple="" name="taskRequired" required>

                                                      <md-optgroup label="Task Required">
                                                        <md-option ng-value="task.id" ng-repeat="task in taskList ">{{task.label}}</md-option>
                                                      </md-optgroup>
                                                    </md-select>
                                                    <div ng-show="careFinderForm.taskRequired.$invalid && careFinderForm.taskRequired.$touched " ng-messages="careFinderForm.shiftDetail.$error" role="alert">
                                                        <div ng-message-exp="['required']">
                                                          Task is required.
                                                        </div>
                                                    </div>
                                                </md-input-container>

                                                <md-input-container class="md-block" flex-gt-sm>
                                                    <label>Other Task</label>
                                                    <input ng-model="careFinderData.taskOther">
                                                </md-input-container>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <md-datepicker ng-model="careFinderData.request.startDate" md-placeholder="Service Start date" md-min-date="minServiceStartDate"></md-datepicker>

                                                    </div>
                                                    <div class="col-md-12">
                                                        <div layout>
                                                            <md-input-container class="md-block">
                                                              <div flex="10" layout layout-align="center center">
                                                                <span class="md-body-1">Duration of service in days</span>
                                                              </div>
                                                              <md-slider flex md-discrete ng-model="careFinderData.request.duration" step="1" min="1" max="100" aria-label="rating">
                                                              </md-slider>
                                                            </md-input-container>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <md-input-container class="md-block" flex-gt-sm>
                                                            <label>Locality</label>
                                                            <input required type="text" name="contactName"  g-places-autocomplete options="autocompleteOptions" ng-model="careFinderData.request.locality" minlength="3" maxlength="100">
                                                            <div ng-show="contactForm.contactName.$invalid && contactForm.contactName.$dirty" ng-messages="contactForm.contactName.$error && contactForm.contactName.$dirty" role="alert">
                                                                <div ng-message-exp="['required', 'minlength', 'maxlength', 'pattern']">
                                                                  Your email must be between 3 and 100 characters long and look like a name.
                                                                </div>
                                                             </div>
                                                        </md-input-container>
                                                        <md-input-container class="md-block">
                                                          <label>Address</label>
                                                          <textarea ng-model="careFinderData.request.address" md-maxlength="150" rows="5" md-select-on-focus></textarea>
                                                        </md-input-container>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <md-button class="md-raised" ng-click="gotoTab(1)">Previous</md-button>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <md-button class="md-raised md-primary pull-right" ng-click="gotoTab(3)">Next</md-button>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>


                                </md-content>
                              </md-tab>
                              <md-tab label="Special Request">
                                  <md-content>
                                    <div class="row">
                                        <div class="col-md-6 col-md-offset-3">
                                            <md-input-container class="md-block" flex-gt-sm>
                                                <md-switch ng-model="careFinderData.request.gender" aria-label="Switch 1" required>
                                                Gender Preferences
                                            </md-switch>
                                            </md-input-container>

                                            <md-input-container ng-show="careFinderData.request.gender">
                                                <label>Gender Preferred:</label>
                                                <md-select ng-model="careFinderData.request.genderRequired">
                                                    <md-option ng-repeat="gender in genders" value="{{gender.value}}">{{gender.label}}</md-option>
                                                </md-select>
                                            </md-input-container>

                                            <md-input-container class="md-block" flex-gt-sm>
                                                <md-switch ng-model="careFinderData.request.religion" aria-label="Switch 2">
                                                Religion Preferences
                                            </md-switch>
                                                </md-input-container>

                                            <md-input-container ng-show="careFinderData.request.religion">
                                                <label>Religion Preferred:</label>
                                                <md-select ng-model="careFinderData.request.religionRequired">
                                                    <md-option ng-repeat="religion in staticData.religions" value="{{religion.id}}">{{religion.label}}</md-option>
                                                </md-select>
                                            </md-input-container>

                                            <md-input-container class="md-block" flex-gt-sm>
                                                <md-switch ng-model="careFinderData.request.language" aria-label="Switch 3">
                                                Language Preferences
                                            </md-switch>
                                                </md-input-container>

                                            <md-input-container ng-show="careFinderData.request.language">
                                                <label>Language Preferred:</label>
                                                <md-select ng-model="careFinderData.request.languageRequired">
                                                    <md-option ng-repeat="language in staticData.languages" value="{{language.id}}">{{language.label}}</md-option>
                                                </md-select>
                                            </md-input-container>

                                            <md-input-container class="md-block" flex-gt-sm>
                                                <md-switch ng-model="careFinderData.request.age" aria-label="Switch 4">
                                                    Age Preferences
                                                </md-switch>
                                            </md-input-container>

                                            <md-input-container ng-show="careFinderData.request.age">
                                                <label>Age Preferred:</label>
                                                <md-select ng-model="careFinderData.request.ageRequired">
                                                    <md-option ng-repeat="age in staticData.ageRanges" value="{{age.id}}">{{age.label}}</md-option>
                                                </md-select>
                                            </md-input-container>
                                            <md-input-container class="md-block" flex-gt-sm>
                                                <md-switch ng-model="careFinderData.request.food" aria-label="Switch 4">
                                                    Food Preferences
                                                </md-switch>
                                            </md-input-container>

                                            <md-input-container ng-show="careFinderData.request.food">
                                                <label>Food Preferred:</label>
                                                <md-select ng-model="careFinderData.request.foodRequired">
                                                    <md-option ng-repeat="food in staticData.foodOptions" value="{{food.id}}">{{food.label}}</md-option>
                                                </md-select>
                                            </md-input-container>

                                            <md-input-container class="md-block">
                                              <label>Remark</label>
                                              <textarea ng-model="careFinderData.request.remark" md-maxlength="150" rows="5" md-select-on-focus></textarea>
                                            </md-input-container>


                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <md-button class="md-raised" ng-click="gotoTab(2)">Previous</md-button>
                                                </div>
                                                <div class="col-xs-6">
                                                    <md-button class="md-raised md-primary pull-right" ng-click="submitRequest()">Submit</md-button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                  </md-content>
                                </md-tab>
                            </md-tabs>

					</div>

				</form>
				</div>
			</div>
		</div>  <!-- Container End -->
    </section>

    </section>
@endsection

