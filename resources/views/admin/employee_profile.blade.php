@extends('layouts.admin.master')
<?php
$menuSelected = "users";
$angularModule = 'userModule';
?>

@section('title')
    Employee Profile
@endsection

@section('content')
    <script>
        var userId = '<% $model['userId'] %>';
    </script>
    <div id="lead-view" ng-controller="userListController">
        <div class="page-title">{{user}}
            <span class="title"></span>
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="title">Personal Details</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-center"><img src="http://52.76.28.11/pramaticare-web/public/user/profile/3?size=small" alt="profile image" class="profile-img"></div>
                                            <div class="spacer-10"></div>
                                            <div class="text-center"><button class="btn btn-primary">Change</button></div>
                                        </div>
                                    </div>
                                    <div class="spacer-10"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <div class="form-group"><input id="name" type="text" placeholder="Enter your name" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <div class="form-group"><input id="phone" type="number" placeholder="Enter your Phone Number" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="age">Age</label>
                                            <div class="form-group"><input id="age" type="number" placeholder="Enter your age" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="weight">Weight</label>
                                            <div class="form-group"><input id="weight" type="number" placeholder="Enter your weight" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="height">Height</label>
                                            <div class="form-group"><input id="height" type="number" placeholder="Enter your height" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="religion">Religion</label>
                                            <div class="form-group"><input id="religion" type="text" placeholder="Enter your religion" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="location">Location</label>
                                            <div class="form-group"><input id="location" type="text" placeholder="Enter your location" class="form-control"></div>
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
                                            <div class="form-group"><input id="shift" type="text" placeholder="Enter your preferred shift" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="address">Bank Details</label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="btn-group">
                                                        <label class="btn btn-success btn-sm" ng-model="" uib-btn-radio="true" uncheckable >Yes</label>
                                                        <label class="btn btn-success btn-sm" ng-model="" uib-btn-radio="false" uncheckable >No</label>
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
                                                    <div class="btn-group">
                                                        <label class="btn btn-success btn-sm" ng-model="" uib-btn-radio="true" uncheckable >Yes</label>
                                                        <label class="btn btn-success btn-sm" ng-model="" uib-btn-radio="false" uncheckable >No</label>
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
                                                    <div class="btn-group">
                                                        <label class="btn btn-success btn-sm" ng-model="" uib-btn-radio="true" uncheckable >Yes</label>
                                                        <label class="btn btn-success btn-sm" ng-model="" uib-btn-radio="false" uncheckable >No</label>
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
                                                    <div class="btn-group">
                                                        <label class="btn btn-success btn-sm" ng-model="" uib-btn-radio="true" uncheckable >Yes</label>
                                                        <label class="btn btn-success btn-sm" ng-model="" uib-btn-radio="false" uncheckable >No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center"><button class="btn btn-primary">Submit</button></div>
                            </form>
                        </div>
                    </div>
                    <div class="spacer-5"></div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="title">Availability</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group" ng-model="available"><input type="checkbox" checked> Coming Soon </div>
                        </div>
                    </div>
                </div>
                <div class="spacer-5"></div>
            </div>
        </div>
        <div class="spacer-5"></div>

    </div>
    </div>

@endsection

@section('pageLevelJS')

    <script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/services/userServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/admin/userscript.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>



@endsection