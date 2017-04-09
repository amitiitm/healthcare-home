<?php
$menuSelected = "enquiries";

$angularModule = 'adminModule';
?>
@extends('layouts.admin.master')



@section('title')
Dashboard
@endsection

@section('content')
    <div ng-controller="adminEnquiriesCtrl">
        <div class="row">
            <div class="col-md-12">
                <md-card>
                    <md-toolbar class="md-table-toolbar md-default" ng-hide="options.rowSelection && selected.length">
                      <div class="md-toolbar-tools">
                        <span>Enquiries</span>
                        <div flex></div>
                        <md-button class="md-icon-button" ng-click="loadStuff()">
                          <md-icon>refresh</md-icon>
                        </md-button>
                      </div>
                    </md-toolbar>

                    <md-toolbar class="md-table-toolbar md-menu-toolbar alternate" ng-show="options.rowSelection && selected.length">
                        <div class="md-toolbar-tools">

                        <h2>
                          <span></span>
                        </h2>
                        <span flex></span>
                        <md-button class="md-raised" aria-label="Learn More">
                          Assign Employee
                        </md-button>
                        <md-button class="md-raised" aria-label="Learn More">
                          View
                        </md-button>
                        <md-button class="md-fab md-mini" aria-label="Favorite">
                            <i class="fa fa-trash"></i>
                        </md-button>
                      </div>
                    </md-toolbar>
                    <md-table-container>
                        <table md-table md-row-select="options.rowSelection" multiple="{{options.multiSelect}}" id="enquiryGrid" class="admin-data-table" ng-model="selected" md-progress="promise">
                            <thead ng-if="!options.decapitate" md-head md-order="query.order" md-on-reorder="logOrder">
                            <tr md-row>
                                <th md-column md-order-by="name"><span>Name</span></th>
                                <th md-column md-order-by="email"><span>Email</span></th>
                                <th md-column md-order-by="phone"><span>Phone</span></th>
                                <th md-column md-order-by="enquiryDate"><span>Enquiry Date</span></th>
                                <th md-column md-order-by="assignedUser.name"><span>Assigned User</span></th>

                            </tr>
                            </thead>
                            <tbody md-body>
	                            <tr md-row md-select="enquiry" md-on-select="logItem" md-auto-select="options.autoSelect"  ng-repeat="enquiry in enquiries.data | filter: filter.search | orderBy: query.order | limitTo: query.limit : (query.page -1) * query.limit">
	                                <td md-cell>{{enquiry.name}}</td>
	                                <td md-cell>{{enquiry.email}}</td>
	                                <td md-cell>{{enquiry.phone}}</td>
	                                <td md-cell>{{enquiry.enquiryDate | carbondate}}</td>
	                                <td md-cell>{{assignedUser.name}}</td>

	                            </tr>
                            </tbody>
                        </table>
                    </md-table-container>
                    <md-table-pagination md-limit="query.limit" md-limit-options="[5, 10, 15]" md-page="query.page" md-total="{{desserts.count}}" md-on-paginate="getEnquiries" md-page-select></md-table-pagination>
                </md-card>
            </div>
        </div>
    </div>


@endsection

@section('pageLevelJS')

<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>


@endsection