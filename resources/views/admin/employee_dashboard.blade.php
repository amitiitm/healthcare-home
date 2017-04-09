<?php
$menuSelected = "dashboard";
?>
@extends('...layouts.admin.master')

@section('title')
    Employee Dashboard
@endsection

@section('content')

    <div>
        <div ng-controller="adminDashboardCtrl" ng-cloak>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <a href="<% url('admin/leads') %>">
                        <div class="card green summary-inline">
                            <div class="card-body">
                                <i class="icon fa fa-inbox fa-4x"></i>
                                <div class="content">
                                    <div class="title">12</div>
                                    <div class="sub-title">Assigned Leads</div>
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
                                    <div class="title">5</div>
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
                                    <div class="title">30</div>
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
                                    <div class="title">306</div>
                                    <div class="sub-title">Available Employees</div>
                                </div>
                                <div class="clear-both"></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('pageLevelJS')


    <script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>

@endsection