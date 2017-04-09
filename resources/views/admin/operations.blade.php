<?php
$menuSelected = "operations";
?>
@extends('layouts.admin.master')



@section('title')
    Dashboard
@endsection

@section('content')
    <div ng-controller="adminOperationsCtrl">
        <div class="row">
            <div class="col-md-12">
                <md-card>
                    <md-toolbar class="md-table-toolbar md-default">
                        <div class="md-toolbar-tools">
                            <span>Coming Soon</span>
                        </div>
                    </md-toolbar>
                    <md-table-container>
                        <!--<table md-table md-row-select="options.rowSelection" multiple="{{options.multiSelect}}" ng-model="selected" md-progress="promise">
                            <thead ng-if="!options.decapitate" md-head md-order="query.order" md-on-reorder="logOrder">
                            <tr md-row>
                                <th md-column md-order-by="name"><span>Name</span></th>
                                <th md-column md-order-by="email"><span>Email</span></th>
                                <th md-column md-numeric md-order-by="role.value" md-desc><span>Role</span></th>
                                <th md-column md-numeric md-order-by="last_login.value"><span>Last Login</span></th>
                                <th md-column md-numeric md-order-by="action.value"><span>Action</span></th>
                                </th>
                            </tr>
                            </thead>
                            <tbody md-body>
                            <tr md-row md-select="dessert" md-on-select="logItem" md-auto-select="options.autoSelect" ng-disabled="dessert.calories.value > 400" ng-repeat="dessert in desserts.data | filter: filter.search | orderBy: query.order | limitTo: query.limit : (query.page -1) * query.limit">
                                <td md-cell></td>
                                <td md-cell></td>
                                <td md-cell></td>
                                <td md-cell></td>
                                <td md-cell></td>
                            </tr>
                            </tbody>
                        </table>
                    </md-table-container>-->
                    <md-table-pagination md-limit="query.limit" md-limit-options="[5, 10, 15]" md-page="query.page" md-total="{{desserts.count}}" md-on-paginate="getDesserts" md-page-select></md-table-pagination>
                </md-card>
            </div>
        </div>
    </div>
    <div id="adminLeadView"  ng-controller="adminLeadsCtrl">
    </div>

@endsection

@section('pageLevelJS')
<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>
        <script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>



@endsection