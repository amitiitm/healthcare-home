<?php
$menuSelected = "employees";

?>
@extends('layouts.admin.master')



@section('title')
    Dashboard
@endsection

@section('content')
    <div ng-controller="adminEmpCtrl">
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
                                <th md-column md-order-by="name"><span>Dessert (100g serving)</span></th>
                                <th md-column md-order-by="type"><span>Type</span></th>
                                <th md-column md-numeric md-order-by="calories.value" md-desc><span>Calories</span></th>
                                <th md-column md-numeric md-order-by="fat.value"><span>Fat (g)</span></th>
                                <th md-column md-numeric md-order-by="carbs.value"><span>Carbs (g)</span></th>
                                <th md-column md-numeric md-order-by="protein.value"><span>Protein (g)</span></th>
                                <th md-column md-numeric md-order-by="sodium.value" hide-gt-xs show-gt-md><span>Sodium (mg)</span></th>
                                <th md-column md-numeric md-order-by="calcium.value" hide-gt-xs show-gt-lg><span>Calcium (%)</span></th>
                                <th md-column md-numeric md-order-by="iron.value" hide-gt-xs show-gt-lg><span>Iron (%)</span></th>
                                <th md-column md-order-by="comment">
                                    <md-icon>comments</md-icon>
                                    <span>Comments</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody md-body>
                            <tr md-row md-select="dessert" md-on-select="logItem" md-auto-select="options.autoSelect" ng-disabled="dessert.calories.value > 400" ng-repeat="dessert in desserts.data | filter: filter.search | orderBy: query.order | limitTo: query.limit : (query.page -1) * query.limit">
                                <td md-cell>{{dessert.name}}</td>
                                <td md-cell>
                                    <md-select ng-model="dessert.type" placeholder="Other">
                                        <md-option ng-value="type" ng-repeat="type in getTypes()">{{type}}</md-option>
                                    </md-select>
                                </td>
                                <td md-cell>{{dessert.calories.value}}</td>
                                <td md-cell>{{dessert.fat.value | number: 2}}</td>
                                <td md-cell>{{dessert.carbs.value}}</td>
                                <td md-cell>{{dessert.protein.value | number: 2}}</td>
                                <td md-cell hide-gt-xs show-gt-md>{{dessert.sodium.value}}</td>
                                <td md-cell hide-gt-xs show-gt-lg>{{dessert.calcium.value}}%</td>
                                <td md-cell hide-gt-xs show-gt-lg>{{dessert.iron.value}}%</td>
                                <td md-cell ng-click="editComment($event, dessert)" ng-class="{'md-placeholder': !dessert.comment}">
                                    {{dessert.comment || 'Add a comment'}}
                                </td>
                            </tr>
                            </tbody>
                        </table>-->
                    </md-table-container>
                    <md-table-pagination md-limit="query.limit" md-limit-options="[5, 10, 15]" md-page="query.page" md-total="{{desserts.count}}" md-on-paginate="getDesserts" md-page-select></md-table-pagination>
                </md-card>
            </div>
        </div>
    </div>
    <div id="adminEmpView"  ng-controller="adminEmpCtrl">
        <div class="new-user-button">
            <md-button class="md-fab md-primary" aria-label="New user" ng-click="showTabDialog($event)">
                <i class="fa fa-plus"></i>
            </md-button>
        </div>
    </div>

@endsection

@section('pageLevelJS')



@endsection