@extends('......layouts.admin.master')
<?php
$menuSelected = "employees";
$angularModule = 'vendorModule';

?>
@section('title')
    Caregiver Profile Edit
@endsection

@section('content')
    <script>

    </script>
    <div id="new-lead-view" ng-controller="VendorDetailController">
        <div class="page-title">
            <span class="title">Edit the details below</span>
            </div>
            <div class="row">
                <form method="post" action="">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="title">Personal Details</div>
                            </div>
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-center"><img src="http://52.76.28.11/pramaticare-web/public/user/profile/1?size=small" alt="profile image" class="profile-img"></div>
                                            <div class="spacer-10"></div>
                                            <div class="text-center"><button class="btn btn-primary">Change</button></div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <div class="form-group"><input id="name" type="text" placeholder="{{vendorData.user.name}}" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <div class="form-group"><input id="phone" type="number" placeholder="{{vendorData.user.phone}}" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label for="age">Age</label>
                                    <div class="form-group"><input id="age" type="number" placeholder="{{vendorData.age}} years" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label for="weight">Weight</label>
                                    <div class="form-group"><input id="weight" type="number" placeholder="{{vendorData.weight}} kg" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label for="height">Height</label>
                                    <div class="form-group"><input id="height" type="number" placeholder="{{vendorData.height}} ft" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label for="religion">Religion</label>
                                    <div class="form-group"><input id="religion" type="text" placeholder="{{vendorData.religion_id}}" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <div class="form-group"><input id="location" type="text" placeholder="{{vendorData.locality_id}}" class="form-control"></div>
                                </div>
                                        </div>
                                    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zone">Zone</label>
                                    <div class="form-group"><input id="zone" type="text" placeholder="Enter your zone" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label for="task">Task Performed</label>
                                    <div class="form-group"><input id="task" type="text" placeholder="Enter the tasks performed" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label for="shift">Preferred Shift</label>
                                    <div class="form-group"><input id="shift" type="text" placeholder="{{vendorData.preferred_shift_id}} hrs" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="address">Bank Details</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="btn-grsoup btn-toggle shift-selector align pull-left">
                                                <label class="btn btn-primary" uib-btn-radio="true" >Yes</label>
                                                <label class="btn btn-primary" uib-btn-radio="false" >No</label>
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
                                            <div class="btn-grsoup btn-toggle shift-selector align pull-left">
                                                <label class="btn btn-primary" uib-btn-radio="true" >Yes</label>
                                                <label class="btn btn-primary" uib-btn-radio="false" >No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="address">A Female Care-Giver</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="btn-grsoup btn-toggle shift-selector align pull-left">
                                                        <label class="btn btn-success btn-grey" uib-btn-radio="true" >Yes</label>
                                                        <label class="btn btn-success btn-grey" uib-btn-radio="false" >No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="address">Coming For Induction Training</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="btn-grsoup btn-toggle shift-selector align pull-left">
                                                <label class="btn btn-success btn-grey" uib-btn-radio="true" >Yes</label>
                                                <label class="btn btn-success btn-grey" uib-btn-radio="false" >No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        </div>
                                    </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="title">Availability</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"><input type="radio" name="avail" ng-model="available"> Available </div>
                                    <div ng-show="available">
                                        <div class="form-group">
                                            <label for="name">Date from when S/he is available :</label>
                                            <div class="form-group"><input id="name" type="date" placeholder="Enter your name" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="religion">Want to work in a different shift</label>
                                            <div class="form-group"><input id="religion" type="text" placeholder="Select shift" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="religion">Wants to work in different location</label>
                                            <div class="form-group"><input id="religion" type="text" placeholder="Select location" class="form-control"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><input type="radio" name="avail" ng-model="unavailable"> Unavailable </div>
                                    <div ng-show="unavailable">
                                        <div class="form-group"><input type="checkbox"> Has left the profession </div>
                                        <div class="form-group"><input type="checkbox"> Blacklisted by Pramaticare </div>
                                        <div class="form-group"><input type="checkbox"> Already employed somewhere </div>
                                        <div class="form-group"><input type="checkbox"> Does not want to work with Pramati </div>
                                            <div ng-show="">
                                                <div class="form-group"><input type="checkbox">Salary issue </div>
                                                <div class="form-group"><input type="checkbox">Location issue </div>
                                                <div class="form-group"><input type="checkbox">Behaviour issue </div>
                                                <div class="form-group"><input type="checkbox">Reputation issue </div>
                                                <div class="form-group"><input type="checkbox">Others </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center"><button class="btn btn-primary">Submit</button></div>
                        </div>
                    </div>
                </form>
            </div>

            </div>
        </div>
@endsection

@section('pageLevelJS')

    <script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/services/vendorServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/services/userServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/admin/vendorscript.js')%>"></script>



@endsection