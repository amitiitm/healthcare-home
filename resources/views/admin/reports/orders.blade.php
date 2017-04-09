<?php
$menuSelected = "orders";
?>
@extends('layouts.admin.master_new')



@section('title')
Orders
@endsection


@section('content')
	<style>
		.grid {
          width: 100%;
        }
	</style>
    <div>
       
        <div class="row">
            
        <div class="ibox float-e-margins">
       
            <div style="display: block;" class="ibox-content">
                <?php if (session()->has('data')) ?>
                    <div class="alert alert-success">
                      <strong>{!!session('data')!!}</strong>
                    </div>
                <?php>
                <table class="table table-striped table-bordered dt-responsive nowrap" id="orders-table" >
                    <thead>
                    <tr>
                        <th>Order#</th>
                        <th>Order Date</th>
                        <th>Service</th>
                        <th>Service Start Date</th>
                        <th>Customer</th>
                        <th>Patient</th>
                        <th>Price</th>
                        <th>Payment</br>Type</th>
                        <th>Payment<br> Period</th>
                        <th>Payment <br>Mode</th>
                        <th>Actions(Invoices)</th>
                       
                    </tr>
                    </thead>
                </table>

            </div>
        </div>
   

        </div>

    </div>


@stop


@section('pageLevelJS')

<script>
$(function() {
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/admin/order_data',
        columns: [
            { data: 'order_number', name: 'order_number' },
            { data: 'created_at', name: 'created_at' },
            { data: 'service_name', name: 'service_name' },
            { data: 'start_date', name: 'start_date' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'patient_name', name: 'patient_name' },
            { data: 'price', name: 'price' },
            { data: 'pmode', name: 'pmode' },
            { data: 'period', name: 'period' },
            { data: 'payment_type', name: 'payment_type' },
            { data: 'action_info', name: 'order_number' },
        ]
    });
});

$(".alert-success").fadeTo(4000, 500).slideUp(500, function(){
    $(".alert-success").slideUp(500);
});
</script>
@endsection
