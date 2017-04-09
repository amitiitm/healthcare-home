<?php
$menuSelected = "Edit Invoice Payment";

use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Edit Invoice Payment
@endsection


@section('content')
<style>
    .grid {
        width: 100%;
    }
    .error{border-color: red;}
    .cheque_payment_div,.expected_payment_date,.paid_payment_type,.unpaid_payment_reason{display: none;}
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Update Payment for Invoice #{!!$invoice->invoice_number!!}</h2>
        <ol class="breadcrumb">
            <li><a href="/home">Home</a></li>
            <li><a href="/admin/assigned_invoices">Assigned Invoices</a></li>
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
                            {!! Form::open(['url' => '/admin/create_invoice_payment','id'=>"update_invoice_payment"]) !!}        
                            <input name="utf8" value="✓" type="hidden">                            
                            <fieldset class="form-horizontal">
                                {!!Form::hidden('invoice_id', $invoice -> id)!!}
                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('customer_name', 'Customer Name:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            <input class="form-control" type="text" value="{!!$invoice -> customer_name!!}" readonly="true">
                                        </div>
                                        {!!Form::label('customer_email', 'Customer Email:*', ['class' => 'col-sm-2 control-label'])!!}                                          
                                        <div class="col-sm-4"> 
                                            <input class="form-control" type="text" value="{!!$invoice -> email!!}" readonly="true">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('customer_address', 'Customer Address:', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            <input class="form-control" type="text" value="{!!$invoice -> address!!}" readonly="true">
                                        </div>
                                        {!!Form::label('customer_mobile', 'Customer Mobile:*', ['class' => 'col-sm-2 control-label'])!!}                                            
                                        <div class="col-sm-4"> 
                                            <input class="form-control" type="text" value="{!!$invoice -> phone!!}" readonly="true">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">
                                        {!!Form::label('total_amount', 'Total Amount:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            <input class="form-control" type="text" value="{!!$invoice -> total_amount!!}" readonly="true" id="total_amount">                                            
                                        </div>
                                        {!!Form::label('remaining_amount', 'Remaining Amount:', ['class' => 'col-sm-2 control-label'])!!}                                           
                                        <div class="col-sm-4"> 
                                            <input class="form-control" type="text" value="{!!$invoice -> remaining_amount!!}" readonly="true">                                                                                           
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-sm-12">                                      
                                        {!!Form::label('payment_status', 'Payment Status:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!! Form::select('payment_status', $payment_statuses,$invoice_payment -> payment_status,['class'=>"form-control",'id' => 'payment_status','placeholder' => '-Please Select payment Status-']) !!}                                            
                                        </div>

                                        <div class="paid_payment_type">
                                            {!!Form::label('payment_mode', 'Payment Mode:*', ['class' => 'col-sm-2 control-label'])!!}
                                            <div class="col-sm-4">  
                                                {!! Form::select('payment_mode_id', $payment_modes,$invoice_payment -> payment_mode_id,['class'=>"form-control",'id' => 'payment_mode_id','placeholder' => '-Please Select Mode of payment-']) !!}                                            
                                            </div>
                                        </div>

                                        <div class="unpaid_payment_reason">
                                            {!!Form::label('unpaid_payment_reason', 'Unpaid Payment Reason:*', ['class' => 'col-sm-2 control-label'])!!}
                                            <div class="col-sm-4">  
                                                {!! Form::select('unpaid_payment_reason', $unpaid_payment_reasons,$invoice_payment -> unpaid_payment_reason,['class'=>"form-control",'id' => 'unpaid_payment_reason','placeholder' => '-Please Select Reason-']) !!}                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group cheque_payment_div paid_payment_type">
                                    <div class="col-sm-12">
                                        {!!Form::label('bank_name', 'Bank Name:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                               
                                            {!!Form::text('bank_name', $invoice_payment -> bank_name,$attributes = ['class'=>"form-control",'id' => 'bank_name'])!!}                                                
                                        </div>
                                        {!!Form::label('cheque_no', 'Cheque Number:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                               
                                            {!!Form::text('cheque_no', $invoice_payment -> cheque_no,$attributes = ['class'=>"form-control",'id' => 'cheque_no'])!!}                                                
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group paid_payment_type">
                                    <div class="col-sm-12">
                                        {!!Form::label('paid_amt', 'Paid Amount:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                               
                                            {!!Form::number('paid_amt', $invoice_payment -> paid_amt,$attributes = ['class'=>"form-control",'id' => 'paid_amt'])!!}                                                
                                        </div>
                                        {!!Form::label('outstanding_amt', 'Outstanding Amount:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">                                               
                                            {!!Form::number('outstanding_amt', $invoice_payment -> outstanding_amt,$attributes = ['class'=>"form-control",'id' => 'outstanding_amt'])!!}                                                
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group paid_payment_type outstanding_amt_type_div">
                                    <div class="col-sm-12">                                      
                                        {!!Form::label('outstanding_amt_type', 'Outstanding Amount Type:*', ['class' => 'col-sm-2 control-label'])!!}
                                        <div class="col-sm-4">  
                                            {!! Form::select('outstanding_amt_type', $outstanding_amt_types,$invoice_payment -> outstanding_amt_type,['class'=>"form-control",'id' => 'outstanding_amt_type','placeholder' => '-Please Select Outstanding Amt Type-']) !!}                                            
                                        </div>
                                        <div class="norecoverable_os_amt_status">                                 
                                            {!!Form::label('nonrecoverable_outstanding_amt_status', 'Non Recoverable Payment Reason:*', ['class' => 'col-sm-2 control-label'])!!}
                                            <div class="col-sm-4">  
                                                {!! Form::select('nonrecoverable_outstanding_amt_status', $nr_outstanding_amt_statuses,$invoice_payment -> nonrecoverable_outstanding_amt_status,['class'=>"form-control",'id' => 'nonrecoverable_outstanding_amt_status','placeholder' => '-Please Select No Recovery Reason-']) !!}                                            
                                            </div>
                                        </div>
                                        <div class="expected_payment_date">                                 
                                            {!!Form::label('expected_payment_date', 'Expected Payment Date:*', ['class' => 'col-sm-2 control-label'])!!}
                                            <div class="col-sm-4"> 
                                                {!!Form::date('expected_payment_date', $invoice_payment -> expected_payment_date,['class'=>"form-control",'id' => 'expected_payment_date'], 'd/m/Y')!!}                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group"><label class="col-sm-2 control-label"></label>
                                    <button class="btn btn-primary " type="button" id="update_payment_button"><i class="fa fa-check"></i>&nbsp;Save</button>                                      
                                    <a href="/admin/assigned_invoices" class="btn btn-white btn-sm">Cancel</a>
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
        checkPaymentMode($('#payment_mode_id').val());
        checkPaymentStatus($('#payment_status').val());
        checkOutstandingType($('#outstanding_amt_type').val());
        checkOutstandingAmt($('#outstanding_amt').val());
    });

    $('#payment_mode_id').on('change', function () {
        checkPaymentMode(this.value);
    });

    $('#payment_status').on('change', function () {
        checkPaymentStatus(this.value);
    });

    $('#outstanding_amt_type').on('change', function () {
        checkOutstandingType(this.value);
    });

    $("#paid_amt").keyup(function () {
        var paid_amt = parseInt($('#paid_amt').val());
        var total_amount = parseInt($('#total_amount').val());
        if(paid_amt > total_amount){
           $('#paid_amt').val(total_amount); 
           var paid_amt = total_amount;
        }
        $('#outstanding_amt').val(total_amount - paid_amt);
        checkOutstandingAmt($('#outstanding_amt').val());
    });

    $("#outstanding_amt").keyup(function () {
        checkOutstandingAmt($('#outstanding_amt').val());
    });

    function checkPaymentMode(payment_mode) {
        if (payment_mode === '2') {
            $('.cheque_payment_div').show();
        } else {
            $('#bank_name').val('');
            $('#cheque_no').val('');
            $('.cheque_payment_div').hide();
        }
    }

    function checkPaymentStatus(payment_status) {
        if (payment_status === '{!!strtolower(PramatiConstants::UNPAID_PAYMENT_STATUS)!!}' || payment_status === '') {
            $('#expected_payment_date').val('');
            $('#nonrecoverable_outstanding_amt_status').val('');
            $('#payment_mode_id').val('');
            $('#bank_name').val('');
            $('#cheque_no').val('');
            $('#paid_amt').val(0);
            $('#outstanding_amt').val(0);
            $('#outstanding_amt_type').val('');
            $('.paid_payment_type').hide();
            if (payment_status !== '') {
                $('.unpaid_payment_reason').show();
            }
        } else if (payment_status === '{!!strtolower(PramatiConstants::PAID_PAYMENT_STATUS)!!}') {
            $('.paid_payment_type').show();
            $('.cheque_payment_div').hide();
            $('.outstanding_amt_type_div').hide();
            $('#unpaid_payment_reason').val('');
            $('.unpaid_payment_reason').hide();
        }
    }

    function checkOutstandingType(outstanding_amt_type) {
        if (outstanding_amt_type === '{!!strtolower(PramatiConstants::NORECOVERABLE_OS_AMT_TYPE)!!}') {
            $('.norecoverable_os_amt_status').show();
            $('.expected_payment_date').hide();
            $('#expected_payment_date').val('');
        } else if (outstanding_amt_type === '{!!strtolower(PramatiConstants::RECOVERABLE_OS_AMT_TYPE)!!}') {
            $('.norecoverable_os_amt_status').hide();
            $('.expected_payment_date').show();
            $('#nonrecoverable_outstanding_amt_status').val('');
        } else {
            $('#nonrecoverable_outstanding_amt_status').val('');
            $('#expected_payment_date').val('');
            $('.norecoverable_os_amt_status').hide();
            $('.expected_payment_date').hide();
        }
    }

    function checkOutstandingAmt(outstanding_amt) {
        var outstanding_amt = parseInt($("#outstanding_amt").val());
        if (outstanding_amt > 0) {
            $('.outstanding_amt_type_div').show();
        } else {
            $('#nonrecoverable_outstanding_amt_status').val('');
            $('#expected_payment_date').val('');
            $('#outstanding_amt_type').val('');
            $('.outstanding_amt_type_div').hide();
        }
    }

    $("#update_payment_button").click(function () {
        $(".form-control").removeClass("error");
        var is_form_valid = true;
        var payment_status = $('#payment_status').val();

        if (payment_status === '' || payment_status === null) {
            is_form_valid = false;
            $('#payment_status').addClass('error');
        } else if (payment_status === '{!!strtolower(PramatiConstants::PAID_PAYMENT_STATUS)!!}') {
            var paid_amt = parseInt($('#paid_amt').val());
            if (paid_amt === '' || paid_amt === null || paid_amt <= 0) {
                is_form_valid = false;
                $('#paid_amt').addClass('error');
            }
            var outstanding_amt = parseInt($('#outstanding_amt').val());
            var total_amount = parseInt($('#total_amount').val());
            if ((outstanding_amt + paid_amt) > total_amount) {
                is_form_valid = false;
                $('#outstanding_amt').addClass('error');
                alert('Sum of Paid & Outstanding Amt : '+ (outstanding_amt + paid_amt) +' cannot be greater than Total Amount : ' + total_amount);
            }
            var payment_mode_id = $('#payment_mode_id').val();
            if (payment_mode_id === '2') {
                var bank_name = $('#bank_name').val();
                var cheque_no = $('#cheque_no').val();
                if (cheque_no === '' || cheque_no === null) {
                    is_form_valid = false;
                    $('#cheque_no').addClass('error');
                }
                if (bank_name === '' || bank_name === null) {
                    is_form_valid = false;
                    $('#bank_name').addClass('error');
                }
            } else if (payment_mode_id === '' || payment_mode_id === null) {
                is_form_valid = false;
                $('#payment_mode_id').addClass('error');
            }

            if (outstanding_amt > 0) {
                var outstanding_amt_type = $('#outstanding_amt_type').val();
                if (outstanding_amt_type === '') {
                    is_form_valid = false;
                    $('#outstanding_amt_type').addClass('error');
                } else if (outstanding_amt_type === '{!!strtolower(PramatiConstants::RECOVERABLE_OS_AMT_TYPE)!!}') {
                    var expected_payment_date = $('#expected_payment_date').val();
                    if (expected_payment_date === '' || expected_payment_date === null) {
                        is_form_valid = false;
                        $('#expected_payment_date').addClass('error');
                    }
                } else if (outstanding_amt_type === '{!!strtolower(PramatiConstants::NORECOVERABLE_OS_AMT_TYPE)!!}') {
                    var nonrecoverable_outstanding_amt_status = $('#nonrecoverable_outstanding_amt_status').val();
                    if (nonrecoverable_outstanding_amt_status === '' || nonrecoverable_outstanding_amt_status === null) {
                        is_form_valid = false;
                        $('#nonrecoverable_outstanding_amt_status').addClass('error');
                    }
                }
            }
        } else if (payment_status === '{!!strtolower(PramatiConstants::UNPAID_PAYMENT_STATUS)!!}') {
            var unpaid_payment_reason = $('#unpaid_payment_reason').val();
            if (unpaid_payment_reason === '' || unpaid_payment_reason === null) {
                is_form_valid = false;
                $('#unpaid_payment_reason').addClass('error');
            }
        }


        if (is_form_valid) {
            $("#update_invoice_payment").submit();
        } else {
            alert('Please fill all necessary fields showing in Red color before submitting.');
        }
    });
</script>
@endsection
