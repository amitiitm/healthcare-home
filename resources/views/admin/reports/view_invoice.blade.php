<?php
$menuSelected = "View Invoice";
?>
@extends('layouts.admin.master_new')



@section('title')
View Invoice
@endsection


@section('content')
<style>
    .grid {
        width: 100%;
    }
    .error{border-color: red;}
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>View Invoice #{!!$invoice->invoice_number!!}</h2>
        <ol class="breadcrumb">
            <li><a href="/home">Home</a></li>
            <li><a href="/admin/invoices">Invoices</a></li>
            <li class="active"><strong>View</strong></li>
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
                            
                            <input name="utf8" value="✓" type="hidden">                            
                            <fieldset class="form-horizontal">
                           
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        {!!Form::label('order', 'Order #:', ['class' => 'col-sm-2 control-label'])!!}                                         
                                        <div class="col-sm-4">
                                            {!!Form::text('order_number', $order -> order_number,$attributes = ['class'=>"form-control",'readonly'=>true])!!}
                                        </div>
                                        {!!Form::label('order_status', 'Order Status:', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">
                                            {!!Form::text('order_status', $order -> order_status,$attributes = ['class'=>"form-control",'readonly'=>true])!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('customer_name', 'Customer Name:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                                
                                            {!!Form::text('customer_name', $invoice -> customer_name,$attributes = ['class'=>"form-control",'id' => 'customer_name','maxlength' => 100,'readonly'=>true])!!}
                                        </div>
                                        {!!Form::label('customer_email', 'Customer Email:*', ['class' => 'col-sm-2 control-label'])!!}                                          
                                        <div class="col-sm-4">                                                
                                            {!!Form::text('email', $invoice -> email,$attributes = ['class'=>"form-control",'id' => 'customer_email','readonly'=>true])!!}
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('customer_address', 'Customer Address:', ['class' => 'col-sm-2 control-label','readonly'=>true])!!}
                                        <div class="col-sm-4">                                               
                                            {!!Form::text('address', $invoice -> address,$attributes = ['class'=>"form-control",'id' => 'customer_address','maxlength' => 500,'readonly'=>true])!!}
                                        </div>
                                        {!!Form::label('customer_landmark', 'Customer Landmark:', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">
                                            {!!Form::text('landmark', $invoice -> landmark,$attributes = ['class'=>"form-control",'id' => 'landmark','maxlength' => 500,'readonly'=>true])!!}                                                

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('customer_mobile', 'Customer Mobile:*', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">                                                
                                            {!!Form::text('phone', $invoice -> phone,$attributes = ['class'=>"form-control",'id' => 'phone','maxlength' => 11,'readonly'=>true])!!}
                                        </div>
                                        {!!Form::label('service_name', 'Service Name:*', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">
                                            @if($service)
                                                <input class="form-control" type="text" value="{!!$service -> name!!}" readonly="true">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('payumoney_url', 'PayU Payment URL:', ['class' => 'col-sm-2 control-label'])!!}

                                        <div class="col-sm-10">
                                            {!!Form::text('payumoney_url', $invoice -> payumoney_url,$attributes = ['class'=>"form-control",'id' => 'payumoney_url','maxlength' => 250,'readonly'=>true])!!}                                                
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('total_amount', 'Total Amount:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                               
                                            {!!Form::number('total_amount', $invoice -> total_amount,$attributes = ['class'=>"form-control",'id' => 'total_amount','readonly'=>true])!!}                                                
                                        </div>
                                        {!!Form::label('remaining_amount', 'Remaining Amount:', ['class' => 'col-sm-2 control-label'])!!}                                           
                                        <div class="col-sm-4">                                                
                                            {!!Form::number('remaining_amount', $invoice -> remaining_amount,$attributes = ['class'=>"form-control",'id' => 'remaining_amount','readonly'=>true])!!}                                                
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('discount_amount', 'Discount Amount:', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                                
                                            {!!Form::number('discount_amount', $invoice -> discount_amount,$attributes = ['class'=>"form-control",'id' => 'discount_amount','readonly'=>true])!!}                                                
                                        </div>
                                        {!!Form::label('tax_amount', 'Tax Amount:', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">                                                
                                            {!!Form::number('tax_amount', $invoice -> tax_amount,$attributes = ['class'=>"form-control",'id' => 'tax_amount','readonly'=>true])!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('price_per_day', 'Price/Day:*', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">                                                
                                            {!!Form::number('price_per_day', $invoice -> price_per_day,$attributes = ['class'=>"form-control",'id' => 'price_per_day','readonly'=>true])!!}
                                        </div>
                                        {!!Form::label('payment_type', 'Payment Type:*', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4"> 
                                            {!! Form::select('payment_type_id', $payment_types,$invoice -> payment_type_id,['class'=>"form-control",'id' => 'payment_type_id','disabled'=>true]) !!}                                                                                        
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('payment_period', 'Payment Period:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">
                                            {!! Form::select('payment_period_id', $payment_periods,$invoice -> payment_period_id,['class'=>"form-control",'id' => 'payment_period_id','disabled'=>true]) !!}                                            
                                        </div>
                                        {!!Form::label('payment_mode', 'Payment Mode:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!! Form::select('payment_mode_id', $payment_modes,$invoice -> payment_mode_id,['class'=>"form-control",'id' => 'payment_mode_id','disabled'=>true]) !!}                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">                                    
                                    <div class="col-sm-12">
                                        {!!Form::label('assigned_user_id', 'Assign Payment Receiver:', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">
                                            {!! Form::select('assigned_user_id', $lead_references,$invoice -> assigned_user_id,['class'=>"form-control",'id' => 'assigned_user_id','disabled'=>true]) !!}                                            
                                        </div>
                                        {!!Form::label('date_of_collection', 'Payment Collection Date:', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!!Form::date('date_of_collection', $invoice -> date_of_collection,['class'=>"form-control",'id' => 'date_of_collection','readonly'=>true], 'd/m/Y')!!}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group ">                                    
                                    <div class="col-sm-12">
                                        {!!Form::label('invoice_from_date', 'Invoice From Date:', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">
                                            <?php $invoice_from_date = date_format(new DateTime($invoice->invoice_from_date), "Y-m-d") ?>
                                            {!!Form::date('invoice_from_date', $invoice_from_date,['class'=>"form-control",'id' => 'invoice_from_date','readonly'=>true], 'd/m/Y')!!}                                            
                                        </div>
                                        {!!Form::label('invoice_to_date', 'Invoice To Date:', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!!Form::date('invoice_to_date', date_format(new DateTime($invoice->invoice_to_date), "Y-m-d"),['class'=>"form-control",'id' => 'invoice_to_date','readonly'=>true], 'd/m/Y')!!}
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if($invoice_payment_collection) { ?>
                                <div class="form-group ">                                    
                                    <div class="col-sm-12">
                                        {!!Form::label('payment_status', 'Payment Status', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">
                                            {!!Form::text('payment_status', $invoice_payment_collection -> payment_status,$attributes = ['class'=>"form-control",'readonly'=>true])!!}                                                
                                        </div>
                                        {!!Form::label('paid_amt', 'Paid Amt', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!!Form::text('paid_amt', $invoice_payment_collection -> paid_amt,$attributes = ['class'=>"form-control",'readonly'=>true])!!} 
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">                                    
                                    <div class="col-sm-12">
                                        {!!Form::label('bank_name', 'Bank Name', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">
                                            {!!Form::text('bank_name', $invoice_payment_collection -> bank_name,$attributes = ['class'=>"form-control",'readonly'=>true])!!}                                                
                                        </div>
                                        {!!Form::label('cheque_no', 'Cheque No.', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!!Form::text('cheque_no', $invoice_payment_collection -> cheque_no,$attributes = ['class'=>"form-control",'readonly'=>true])!!} 
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">                                    
                                    <div class="col-sm-12">
                                        {!!Form::label('created_at', 'Payment Collection Date', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">
                                            {!!Form::date('created_at', $invoice_payment_collection -> created_at,['class'=>"form-control",'readonly'=>true], 'd/m/Y')!!}
                                        </div>
                                        {!!Form::label('outstanding_amt', 'Outstanding Amt', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!!Form::text('outstanding_amt', $invoice_payment_collection -> outstanding_amt,$attributes = ['class'=>"form-control",'readonly'=>true])!!} 
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                
                                

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('assigned_user_comments', 'Payment Receiver Comments:', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-10">  
                                            {!!Form::text('assigned_user_comments', $invoice -> assigned_user_comments,$attributes = ['class'=>"form-control",'maxlength' => 500,'readonly'=>true])!!}
                                        </div>
                                    </div>
                                </div>

                               
                            </fieldset>

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

    $(function () {
        $("#date_of_collection").datepicker({
            changeMonth: true,
            changeYear: true
        });
    });

    $("#update_invoice_button").click(function () {
        $(".form-control").removeClass("error");
        var is_form_valid = true;

        var customer_name = $('#customer_name').val();
        if (customer_name === '' || customer_name === null) {
            is_form_valid = false;
            $('#customer_name').addClass('error');
        }

        var customer_email = $('#customer_email').val();
        if (customer_email === '' || customer_email === null) {
            is_form_valid = false;
            $('#customer_email').addClass('error');
        }

        var phone = $('#phone').val();
        if (phone === '' || phone === null) {
            is_form_valid = false;
            $('#phone').addClass('error');
        }

        var payumoney_url = $('#payumoney_url').val();
        if (payumoney_url === '' || payumoney_url === null) {
            is_form_valid = false;
            $('#payumoney_url').addClass('error');
        }

        var total_amount = $('#total_amount').val();
        if (total_amount === '' || total_amount === null || total_amount <= 0) {
            is_form_valid = false;
            $('#total_amount').addClass('error');
        }

        var price_per_day = $('#price_per_day').val();
        if (price_per_day === '' || price_per_day === null || price_per_day <= 0) {
            is_form_valid = false;
            $('#price_per_day').addClass('error');
        }

        var payment_type_id = $('#payment_type_id').val();
        if (payment_type_id === '' || payment_type_id === null) {
            is_form_valid = false;
            $('#payment_type_id').addClass('error');
        }

        var payment_period_id = $('#payment_period_id').val();
        if (payment_period_id === '' || payment_period_id === null) {
            is_form_valid = false;
            $('#payment_period_id').addClass('error');
        }

        var payment_mode_id = $('#payment_mode_id').val();
        if (payment_mode_id === '' || payment_mode_id === null) {
            is_form_valid = false;
            $('#payment_mode_id').addClass('error');
        }

        if (is_form_valid) {
            $("#edit_invoice").submit();
        } else {
            alert('Please fill all necessary fields before submitting.');
        }
    });
</script>
@endsection
