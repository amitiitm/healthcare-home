<?php
$menuSelected = "replacement_requests";
$angularModule = 'adminModule';
?>
@extends('layouts.admin.master')



@section('title')
Replacement Requests
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

<div ng-controller="replacementRequestsCtrl" id="adminLeadView">
    <div class="page-title">
        <span class="title">Replacement Requests</span>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive">
               <div id="grid1" ui-grid="gridOptionsReplacementRequests" class="grid"></div>
            </div>

        </div>
    </div>
</div>


@endsection

@section('pageLevelJS')

<script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
<script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>

@endsection