<?php
$menuSelected = "enquiries";
use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Edit Followup
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
        <h2>Edit Followup</h2>
        <ol class="breadcrumb">
            <li><a href="/home">Home</a></li>
            <li><a href="/admin/enquiries1">Followups</a></li>
            <li class="active"><strong>Edit</strong></li>
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
                            {!! Form::open(['url' => '/admin/enquiry/update','id'=>"create_enquiry"]) !!}        
                            <input name="utf8" value="✓" type="hidden">                            
                            <fieldset class="form-horizontal">
                                {!!Form::hidden('id', $enquiry -> id)!!}
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        {!!Form::label('name', 'Name:*', ['class' => 'col-sm-2 control-label'])!!}                                         
                                        <div class="col-sm-4">
                                            {!!Form::text('name', $enquiry->name,$attributes = ['class'=>"form-control"])!!}
                                        </div>
                                        {!!Form::label('email', 'Email:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">
                                            {!!Form::text('email', $enquiry->email,$attributes = ['class'=>"form-control",'maxlength' => 100])!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('mobile', 'Mobile:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                                
                                            {!!Form::text('phone', $enquiry->phone,$attributes = ['class'=>"form-control",'maxlength' => 11,'id' => 'phone'])!!}
                                        </div>
                                        {!!Form::label('status', 'Status:', ['class' => 'col-sm-2 control-label'])!!}                                          
                                        <div class="col-sm-4">                                                
                                            {!! Form::select('status', $statuses,$enquiry->status,['class'=>"form-control",'id' => 'status']) !!}                                                                                        
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        {!!Form::label('user_type', 'User Type:', ['class' => 'col-sm-2 control-label'])!!}                                          
                                        <div class="col-sm-4">                                                
                                            {!! Form::select('user_type', $user_types,$enquiry->user_type,['class'=>"form-control",'id' => 'user_type']) !!}                                                                                        
                                        </div>
                                        <div class="followup_date_div">
                                            {!!Form::label('followup_date', 'FollowUp Date:', ['class' => 'col-sm-2 control-label'])!!}
                                            <div class="col-sm-4">  
                                                {!!Form::date('followup_time', $enquiry->followup_time,['class'=>"form-control",'id' => 'followup_time'], 'd/m/Y')!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('message', 'Message:', ['class' => 'col-sm-2 control-label'])!!}

                                        <div class="col-sm-10">
                                            {!!Form::text('message', $enquiry->message,$attributes = ['class'=>"form-control",'maxlength' => 250])!!}                                                
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group"><label class="col-sm-2 control-label"></label>
                                    <button class="btn btn-primary " type="button" id="create_enquiry_button"><i class="fa fa-check"></i>&nbsp;Update Followup</button>                                      
                                    <a href="/admin/enquiries1" class="btn btn-white btn-sm">Cancel</a>
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
    $(document).ready(function () {
        changeStatus($('#status').val());
    });
    
    $('#status').on('change', function () {
        changeStatus(this.value);
    });
    
    function changeStatus(status){
       if (status == "{!!strtolower(PramatiConstants::FOLLOW_UP)!!}") {
           $('.followup_date_div').show();
        }else{
           $('.followup_date_div').hide(); 
        } 
    }

    $("#create_enquiry_button").click(function () {
        $(".form-control").removeClass("error");
        var is_form_valid = true;
        
        var name = $('#name').val();
        if (name === '' || name === null) {
            is_form_valid = false;
            $('#name').addClass('error');
        }
        
        var email = $('#email').val();
        if (email === '' || email === null) {
            is_form_valid = false;
            $('#email').addClass('error');
        }
        
        var phone = $('#phone').val();
        if (phone === '' || phone === null) {
            is_form_valid = false;
            $('#phone').addClass('error');
        }

        if (is_form_valid) {
            $("#create_enquiry").submit();
        } else {
            alert('Please fill all necessary fields before submitting.');
        }
    });
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
