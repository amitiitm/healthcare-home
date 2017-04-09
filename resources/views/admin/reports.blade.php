<?php
$menuSelected = "reports";
?>
@extends('layouts.admin.master')



@section('title')
    Reports
@endsection

@section('content')
<div ng-controller="adminReportCtrl">
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Physio Calander </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">View</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Collection Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Summary Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Attendance Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Client Master </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Critical Cases Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Daily Activity Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Daily Customer Movement </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">New Account Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Online Leads Reports </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Source Activity Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Closure Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Feedback Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Physio Attendance Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Comprehensive Report </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div><div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Date-wise Active Clients </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <a href="#">
                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="report-card">Client-wise Attendance </span>
                        </md-card-title-text>
                        <div></div>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button class="report-btn">Action 1</md-button>
                        </md-card-actions>
                    </md-card-title>
                </md-card>
            </a>
        </div>
    </div>
</div>

@endsection

@section('pageLevelJS')

<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>


@endsection