<?php
$menuSelected = "users";

$angularModule = 'userModule';
?>
@extends('layouts.admin.master')



@section('title')
Dashboard
@endsection


@section('content')
	<style>
		.grid {
          width: 100%;
        }
	</style>
    <div ng-controller="userListController">
        <div class="row">
            <div class="col-md-12">
				<div class="table-responsive">
					<div id="grid1" ui-grid="gridOptions" class="grid"></div>
				</div>
            </div>
        </div>



        <div class="new-user-button">
			<md-button aria-label="menu" class="md-fab md-primary" ng-click="createNewUser($event)">
	            <i class="fa fa-plus"></i>
	        </md-button>
        </div>
    </div>

</div>

@endsection

@section('pageLevelJS')

<script type="text/javascript" src="<% asset('static/js/services/userServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/userscript.js')%>"></script>


@endsection