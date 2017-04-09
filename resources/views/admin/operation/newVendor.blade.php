@extends('layouts.admin.master')
<?php
$menuSelected = "employees";
$angularModule = 'vendorModule';

?>
@section('title')
    New Vendor
@endsection

@section('content')
    <div id="new-lead-view" ng-controller="VendorAddController">
        <div class="page-title">
            <span class="title">Add New Caregiver</span>{{vendorData}}
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
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-4">
                            <div class="sub-title required">Name</div>
                            <div>
                                <input type="text" class="form-control" ng-model="vendorData.name" placeholder="Name" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.Name.message}}" tooltip-is-open="!vendorDataValidation.VendorName.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                            </div>
                            <div class="sub-title">Email</div>
                            <div>
                                <input type="email" class="form-control" ng-model="vendorData.email"  placeholder="Email">
                            </div>
                            <div class="sub-title required">Phone Number</div>
                            <div>
                                <input type="text" class="form-control" ng-model="vendorData.phone"  placeholder="Phone Number" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.Phone.message}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                            </div>
                            <div class="sub-title required">Alternate Number</div>
                            <div>
                                <input type="text" class="form-control" ng-model="vendorData.alternate_no"  placeholder="Alternate Number">
                            </div>
                            <div class="sub-title required">Age <small>(in years)</small></div>
                            <div>
                                <input type="text" class="form-control" ng-model="vendorData.age"  placeholder="Age" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.Age.message}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                            </div>
                            <div class="sub-title required">Weight <small>(in kgs)</small></div>
                            <div>
                                <input type="number" class="form-control" ng-model="vendorData.weight"  placeholder="Weight" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.Weight}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                            </div>
                            <div class="sub-title required">Height <small>(in feets)</small></div>
                            <div>
                                <input type="number" class="form-control" ng-model="vendorData.height"  placeholder="Height" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.Height}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="sub-title required">Religion</div>
                            <div>
                                <ui-select ng-model="vendorData.religion_id"  theme="bootstrap">
                                    <ui-select-match placeholder="Select Religion">{{$select.selected.label}}</ui-select-match>
                                    <ui-select-choices repeat="item in mappedData.references | filter: $select.search">
                                        <div ng-bind-html="item.label | highlight: $select.search"></div>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div class="sub-title required">Address</div>
                            <div>
                                <input type="text" class="form-control" ng-model="vendorData.address"  placeholder="Address" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.x}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                            </div>
                            <div class="sub-title required">Locality</div>
                            <div>
                                <input type="text" class="form-control" ng-model="vendorData.locality_id"  placeholder="Locality" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.x}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                            </div>
                            <div class="sub-title required">Qualifications</div>
                            <div>
                                <ui-select ng-model="vendorData.qualifcation_id"  theme="bootstrap">
                                    <ui-select-match placeholder="Select qualifcation">{{$select.selected.label}}</ui-select-match>
                                    <ui-select-choices repeat="item in mappedData.references | filter: $select.search">
                                        <div ng-bind-html="item.label | highlight: $select.search"></div>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div class="sub-title required">Experience</div>
                            <div>
                                <input type="text" class="form-control" ng-model="vendorData.experience"  placeholder="Experience" tooltip-class="tooltip-error" uib-tooltip="{{vendorDataValidation.x}}" tooltip-is-open="!vendorDataValidation.VendorPhone.valid" tooltip-trigger="none" tooltip-placement="bottom-left">
                            </div>
                            <div class="sub-title required">Preferred Shift</div>
                            <div>
                                <ui-select ng-model="vendorData.preferred_shift"  theme="bootstrap">
                                    <ui-select-match placeholder="Select preferred shift">{{$select.selected.label}}</ui-select-match>
                                    <ui-select-choices repeat="item in mappedData.references | filter: $select.search">
                                        <div ng-bind-html="item.label | highlight: $select.search"></div>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div class="sub-title required">Location Of Work</div>
                            <div>
                                <ui-select ng-model="vendorData.location_of_work"  theme="bootstrap">
                                    <ui-select-match placeholder="Select location of work">{{$select.selected.label}}</ui-select-match>
                                    <ui-select-choices repeat="item in mappedData.references | filter: $select.search">
                                        <div ng-bind-html="item.label | highlight: $select.search"></div>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="sub-title required">Category</div>
                            <div>
                                <ui-select ng-model="vendorData.category_id"  theme="bootstrap">
                                    <ui-select-match placeholder="Select category">{{$select.selected.label}}</ui-select-match>
                                    <ui-select-choices repeat="item in mappedData.references | filter: $select.search">
                                        <div ng-bind-html="item.label | highlight: $select.search"></div>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div class="sub-title required">Agency</div>

                            <div class="sub-title required">Food Preferrence</div>
                            <div>
                                <ui-select ng-model="vendorData.food_type_id"  theme="bootstrap">
                                    <ui-select-match placeholder="Food Preferrence">{{$select.selected.label}}</ui-select-match>
                                    <ui-select-choices repeat="item in mappedData.references | filter: $select.search">
                                        <div ng-bind-html="item.label | highlight: $select.search"></div>
                                    </ui-select-choices>
                                </ui-select>
                            </div>
                            <div class="sub-title required">Smart Phone</div>
                            <div>
                                <div class="btn-group">
                                    <label class="btn btn-success btn-sm" ng-model="vendorData.has_smart_phone" uib-btn-radio="true" uncheckable>Yes</label>
                                    <label class="btn btn-success btn-sm" ng-model="vendorData.has_smart_phone" uib-btn-radio="false" uncheckable>No</label>
                                </div>
                            </div>
                            <div class="sub-title required">Has bank Account</div>
                            <div>
                                <div class="btn-group">
                                    <label class="btn btn-success btn-sm" ng-model="vendorData.has_bank_account" uib-btn-radio="true" uncheckable>Yes</label>
                                    <label class="btn btn-success btn-sm" ng-model="vendorData.has_bank_account" uib-btn-radio="false" uncheckable>No</label>
                                </div>
                            </div>
                            <div class="sub-title required">Attended Training</div>
                            <div>
                                <div class="btn-group">
                                    <label class="btn btn-success btn-sm" ng-model="vendorData.training_attended" uib-btn-radio="true" uncheckable>Yes</label>
                                    <label class="btn btn-success btn-sm" ng-model="vendorData.training_attended" uib-btn-radio="false" uncheckable>No</label>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

@section('pageLevelJS')

    <script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/admin/vendorscript.js')%>"></script>



@endsection