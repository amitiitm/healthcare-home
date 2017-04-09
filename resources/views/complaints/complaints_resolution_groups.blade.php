<?php
$menuSelected = "complaints_resolution_groups";
$angularModule = 'adminModule';
?>
@extends('layouts.admin.master')



@section('title')
Complaints
@endsection

@section('content')
<style>
  .grid {
      width: 100%;
  }
  .ui-grid-render-container-body {
    position: absolute;
    margin-left: 31px;
}

.ui-grid-grid-footer {
    position: absolute;
    bottom: 25px;
}
</style>

<div ng-controller="adminResolutionGroupsMembersCtrl" id="adminLeadView">
	<div class="page-title">
        <span class="title">Complaints Resolution Groups</span>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive">
               <div id="grid1" ui-grid="gridOptionsResolutionGroups" class="grid"></div>
            </div>

        </div>
    </div>
    <div class="new-lead-button">
        <md-button class="md-fab md-primary" aria-label="Use Android" ng-click="addResolutionGroupMember()">
          <i class="fa fa-plus"></i>
        </md-button>
    </div>
</div>

<script type="text/ng-template" id="addResolutionGroupMember.html">
        <div class="modal-header">
            <h3 class="modal-title">Allot Employee</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="sub-title">Employee Name</div>
                    <div>
                        <ui-select ng-model="addMemberFromData.user_id" theme="bootstrap">
                            <ui-select-match placeholder="Select Employee">{{$select.selected.name}} - {{$select.selected.phone}} - {{$select.selected.email}}</ui-select-match>
                            <ui-select-choices repeat="item.id as item in usersList | filter: $select.search">
                                {{item.name}} - {{item.phone}} - {{item.email}} / {{item.id}}
                            </ui-select-choices>
                        </ui-select>
                    </div>

                    <div class="sub-title">Group</div>
                    <div>
                        <ui-select ng-model="addMemberFromData.group_id" theme="bootstrap">
                            <ui-select-match placeholder="Select Group">{{$select.selected.group_name}}</ui-select-match>
                            <ui-select-choices repeat="item.id as item in groupList | filter: $select.search">
                                {{item.group_name}}
                            </ui-select-choices>
                        </ui-select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-click="submitAddMember()" ng-disabled="!addMemberFromData.user_id || !addMemberFromData.group_id">Add</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>

@endsection

@section('pageLevelJS')

<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>

@endsection