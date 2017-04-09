<?php
$menuSelected = "feedbacks";
use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Add Feedback
@endsection


@section('content')
<style>
    .grid {
        width: 100%;
    }
    .error{border-color: red;}
    .payment_collector_div{display: none;}
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Add New Feedback</h2>
        <ol class="breadcrumb">
            <li><a href="/home">Home</a></li>
            <li><a href="/admin/lead/feedback">Feedbacks</a></li>
            <li class="active"><strong>Add</strong></li>
        </ol>	
    </div>

</div>

<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">

                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            {!! Form::open(['url' => '/admin/lead/feedback/create','id'=>"create_feedback"]) !!} 
                            {!!Form::hidden('lead_name', '', array('id' => 'lead_name'))!!}
                            {!!Form::hidden('patient_id', '', array('id' => 'patient_id'))!!}
                            {!!Form::hidden('caregiver_id', '', array('id' => 'caregiver_id'))!!}
                            {!!Form::hidden('employee_id', '', array('id' => 'employee_id'))!!}
                            <input name="utf8" value="✓" type="hidden">                            
                            <fieldset class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        {!!Form::label('lead_id', 'Lead Name:*', ['class' => 'col-sm-2 control-label'])!!}                                         
                                        <div class="col-sm-4">
                                            {!! Form::select('lead_id', $leads,'',['class'=>"form-control data-live-search='true'",'id' => 'lead_id']) !!}
                                        </div>
                                        {!!Form::label('lead_mobile', 'Lead Mobile:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">
                                            {!!Form::text('lead_mobile', '',$attributes = ['class'=>"form-control",'maxlength' => 12,'id' => 'lead_mobile'])!!}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        {!!Form::label('patient_name', 'Patient Name:*', ['class' => 'col-sm-2 control-label'])!!}                                         
                                        <div class="col-sm-4">
                                            {!!Form::text('patient_name', '',$attributes = ['class'=>"form-control",'id' => 'patient_name'])!!}
                                        </div>
                                        {!!Form::label('caregiver_name', 'Caregiver Name:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">
                                            {!!Form::text('caregiver_name', '',$attributes = ['class'=>"form-control",'id' => 'caregiver_name'])!!}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        {!!Form::label('status', 'Status:*', ['class' => 'col-sm-2 control-label'])!!}                                         
                                        <div class="col-sm-4">
                                            {!! Form::select('status', $statuses,'',['class'=>"form-control data-live-search='true'",'id' => 'status']) !!}
                                        </div>
                                        {!!Form::label('feedback_date', 'Feedback Date:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">
                                             {!!Form::date('feedback_date', '',['class'=>"form-control",'id' => 'feedback_date'], 'd/m/Y')!!}
                                        </div>
                                    </div>
                                </div>

                                                           

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('employee_name', 'Employee Assigned:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">
                                            {!!Form::text('employee_name', '',$attributes = ['class'=>"form-control",'id' => 'employee_name'])!!}
                                        </div>
                                        {!!Form::label('remarks', 'Remarks:', ['class' => 'col-sm-2 control-label'])!!}

                                        <div class="col-sm-4">
                                            {!!Form::text('remarks', '',$attributes = ['class'=>"form-control",'maxlength' => 1000])!!}                                                
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group"><label class="col-sm-2 control-label"></label>
                                    <button class="btn btn-primary " type="button" id="create_feedback_button"><i class="fa fa-check"></i>&nbsp;Submit Feedback</button>                                      
                                    <a href="/admin/lead/feedback" class="btn btn-white btn-sm">Cancel</a>
                                </div>
                            </fieldset>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection
@section('pageLevelJS')
<script type="text/javascript">    
    $('#lead_id').on('change', function () {
        var leadId = this.value;
        $.ajax({
               type:'GET',
               url:'/admin/lead_details/' + leadId,
               success:function(data){  
                   //$('#lead_id').val(data['data']['lead_id']);
                   $('#lead_name').val(data['data']['lead_name']);
                   $('#lead_mobile').val(data['data']['lead_mobile']);
                   $('#patient_id').val(data['data']['patient_id']);
                   $('#patient_name').val(data['data']['patient_name']);
                   $('#caregiver_id').val(data['data']['caregiver_id']);
                   $('#caregiver_name').val(data['data']['caregiver_name']);
                   $('#employee_id').val(data['data']['employee_id']);
                   $('#employee_name').val(data['data']['employee_name']);
               }
            });   
    });

    $("#create_feedback_button").click(function () {
        $(".form-control").removeClass("error");
        var is_form_valid = true;
        
        var remarks = $('#remarks').val();
        if (remarks === '' || remarks === null) {
            is_form_valid = false;
            $('#remarks').addClass('error');
        }
        
        var lead_id = $('#lead_id').val();
        if (lead_id === '' || lead_id === null) {
            is_form_valid = false;
            $('#lead_id').addClass('error');
        }
        
        if (is_form_valid) {
            $("#create_feedback").submit();
        } else {
            alert('Please fill all necessary fields before submitting.');
        }
    });
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
