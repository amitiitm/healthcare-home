<?php
$menuSelected = "Edit Invoice";
?>
@extends('layouts.admin.master_new')



@section('title')
Edit Invoice
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
        <h2>Edit Invoice #{!!$invoice->invoice_number!!}</h2>
        <ol class="breadcrumb">
            <li><a href="/home">Home</a></li>
            <li><a href="/admin/invoices">Invoices</a></li>
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
                            {!! Form::open(['url' => '/admin/update_invoice','id'=>"edit_invoice"]) !!}        
                            <input name="utf8" value="✓" type="hidden">                            
                            <fieldset class="form-horizontal">
                                {!!Form::hidden('id', $invoice -> id)!!}
                                {!!Form::hidden('referer', $referer)!!}
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
                                            {!!Form::text('customer_name', $invoice -> customer_name,$attributes = ['class'=>"form-control",'id' => 'customer_name','maxlength' => 100])!!}
                                        </div>
                                        {!!Form::label('customer_email', 'Customer Email:*', ['class' => 'col-sm-2 control-label'])!!}                                          
                                        <div class="col-sm-4">                                                
                                            {!!Form::text('email', $invoice -> email,$attributes = ['class'=>"form-control",'id' => 'customer_email','readonly'=>true])!!}
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('customer_address', 'Customer Address:', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                               
                                            {!!Form::text('address', $invoice -> address,$attributes = ['class'=>"form-control",'id' => 'customer_address','maxlength' => 500])!!}
                                        </div>
                                        {!!Form::label('customer_landmark', 'Customer Landmark:', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">
                                            {!!Form::text('landmark', $invoice -> landmark,$attributes = ['class'=>"form-control",'id' => 'landmark','maxlength' => 500])!!}                                                

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('customer_mobile', 'Customer Mobile:*', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">                                                
                                            {!!Form::text('phone', $invoice -> phone,$attributes = ['class'=>"form-control",'id' => 'phone','maxlength' => 11])!!}
                                        </div>
                                        {!!Form::label('service_name', 'Service Name:*', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">
                                            <input class="form-control" type="text" value="{!!$service -> name!!}" readonly="true">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('payumoney_url', 'PayU Payment URL:', ['class' => 'col-sm-2 control-label'])!!}

                                        <div class="col-sm-10">
                                            {!!Form::text('payumoney_url', $invoice -> payumoney_url,$attributes = ['class'=>"form-control",'id' => 'payumoney_url','maxlength' => 250,'disabled' => true])!!}                                                
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('total_amount', 'Total Amount:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                               
                                            {!!Form::number('total_amount', $invoice -> total_amount,$attributes = ['class'=>"form-control",'id' => 'total_amount'])!!}                                                
                                        </div>
                                        {!!Form::label('remaining_amount', 'Remaining Amount:', ['class' => 'col-sm-2 control-label'])!!}                                           
                                        <div class="col-sm-4">                                                
                                            {!!Form::number('remaining_amount', $invoice -> remaining_amount,$attributes = ['class'=>"form-control",'id' => 'remaining_amount'])!!}                                                
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('discount_amount', 'Discount Amount:', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                                
                                            {!!Form::number('discount_amount', $invoice -> discount_amount,$attributes = ['class'=>"form-control",'id' => 'discount_amount'])!!}                                                
                                        </div>
                                        {!!Form::label('tax_amount', 'Tax Amount:', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">                                                
                                            {!!Form::number('tax_amount', $invoice -> tax_amount,$attributes = ['class'=>"form-control",'id' => 'tax_amount'])!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('price_per_day', 'Price/Day:*', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">                                                
                                            {!!Form::number('price_per_day', $invoice -> price_per_day,$attributes = ['class'=>"form-control",'id' => 'price_per_day'])!!}
                                        </div>
                                        {!!Form::label('payment_type', 'Payment Type:*', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4"> 
                                            {!! Form::select('payment_type_id', $payment_types,$invoice -> payment_type_id,['class'=>"form-control",'id' => 'payment_type_id']) !!}                                                                                        
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('payment_period', 'Payment Period:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">
                                            @if($referer == 'admin')
                                                {!! Form::select('payment_period_id', $payment_periods,$invoice -> payment_period_id,['class'=>"form-control",'id' => 'payment_period_id']) !!} 
                                            @else
                                                {!! Form::select('payment_period_id', $payment_periods,$invoice -> payment_period_id,['class'=>"form-control",'id' => 'payment_period_id','disabled' => true]) !!}                                            
                                            @endif
                                        </div>
                                        {!!Form::label('payment_mode', 'Payment Mode:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!! Form::select('payment_mode_id', $payment_modes,$invoice -> payment_mode_id,['class'=>"form-control",'id' => 'payment_mode_id','placeholder' => '-Please Select Mode of payment-']) !!}                                            
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">                                                                       
                                        @if($edit_after_lead_closed || ($referer && $referer == 'admin'))
                                        {!!Form::label('invoice_from_date', 'Invoice From Date:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!!Form::date('invoice_from_date', $invoice -> invoice_from_date,['class'=>"form-control",'id' => 'invoice_from_date'], 'd/m/Y')!!}
                                        </div>
                                        {!!Form::label('invoice_to_date', 'Invoice To Date:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!!Form::date('invoice_to_date', $invoice -> invoice_to_date,['class'=>"form-control",'id' => 'invoice_to_date'], 'd/m/Y')!!}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group payment_collector_div">                                    
                                    <div class="col-sm-12">
                                        {!!Form::label('assigned_user_id', 'Assign Payment Receiver:', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4">
                                            {!! Form::select('assigned_user_id', $lead_references,$invoice -> assigned_user_id,['class'=>"form-control",'id' => 'assigned_user_id', 'placeholder' => '-Please Select Payment Collector-']) !!}                                            
                                        </div>
                                        {!!Form::label('date_of_collection', 'Payment Collection Date:', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!!Form::date('date_of_collection', $invoice -> date_of_collection,['class'=>"form-control",'id' => 'date_of_collection'], 'd/m/Y')!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group payment_collector_div">
                                    <div class="col-sm-12">
                                        {!!Form::label('assigned_user_comments', 'Payment Receiver Comments:', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-10">  
                                            {!!Form::text('assigned_user_comments', $invoice -> assigned_user_comments,$attributes = ['class'=>"form-control",'maxlength' => 500])!!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group"><label class="col-sm-2 control-label"></label>
                                    <button class="btn btn-primary " type="button" id="update_invoice_button"><i class="fa fa-check"></i>&nbsp;Update Invoice</button>                                      
                                    <a href="/admin/invoices" class="btn btn-white btn-sm">Cancel</a>
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
        paymentCollector($('#payment_mode_id').val());
    });

    $(function () {
        //$("#date_of_collection").datepicker();
    });

    $('#payment_mode_id').on('change', function () {
        paymentCollector(this.value);
    });

    function paymentCollector(payment_mode) {
        if (payment_mode !== '3') {
            $('.payment_collector_div').show();
        } else {
            $('#assigned_user_id').val('');
            $('#date_of_collection').val('');
            $('.payment_collector_div').hide();
        }
    }


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

        var payment_mode_id = $('#payment_mode_id').val();
        if (payment_mode_id === '' || payment_mode_id === null) {
            is_form_valid = false;
            $('#payment_mode_id').addClass('error');
        }

        //removing this check as url is autogenerated
        if (payment_mode_id == '3') {
            var payumoney_url = $('#payumoney_url').val();
            if (payumoney_url === '' || payumoney_url === null) {
                //is_form_valid = false;
                //$('#payumoney_url').addClass('error');
            }
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

        if (payment_mode_id !== '3') {
            var assigned_user_id = $('#assigned_user_id').val();
            var date_of_collection = $('#date_of_collection').val();
            if (assigned_user_id === '' || assigned_user_id === null) {
                is_form_valid = false;
                $('#assigned_user_id').addClass('error');
            }
            if (date_of_collection === '' || date_of_collection === null) {
                is_form_valid = false;
                $('#date_of_collection').addClass('error');
            }
        }

        if (is_form_valid) {
            $("#edit_invoice").submit();
        } else {
            alert('Please fill all necessary fields before submitting.');
        }
    });
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
