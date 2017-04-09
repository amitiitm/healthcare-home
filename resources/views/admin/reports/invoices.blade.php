
<?php
$menuSelected = "invoices";

use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Invoices
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

                <table class="table table-striped table-bordered dt-responsive nowrap" id="invoice_table" >
                    <thead>
                        <tr><a href="/admin/orders">Back to Orders</a></tr>
                    <tr>
                        <th>Order#</th>
                        <th>Invoice#</th>
                        <th>Inv Date</th>
                        <th>Cust Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Price/Day</th>
                        <th>Inv Amt</th>
                        <th>Status</th>
                        <th>AssignedTo</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div>


    </div>

</div>


@endsection

@section('pageLevelJS')
<script type="text/javascript">
    
    $(document).ready(function() {
        $('#invoice_table').on('click', '.invoice_status', function (e) {
            var r = confirm("Are you sure you want to Approve this Invoice ?");
            if (r == true) {
                var invoice_id = $(this).attr('data-value');
                window.location.href = '/admin/approve_invoice/'+invoice_id;
            }
        
        });
    } );
    $(function() {
        
       
        
        $('#invoice_table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            stateDuration: 60 * 60 * 24,
            searchDelay: 2000,
            ajax: '/admin/invoice_data',
            columns: [
                { data: 'order_number', name: 'order_number' },
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'created_at', name: 'created_at' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'address', name: 'address',"width": "5%" },
                { data: 'price_per_day', name: 'price_per_day' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'status', name: 'status' },
                { data: 'assigned_to', name: 'assigned_user_id' },
                { data: 'action_info', name: 'order_number' }
            ]
        });
    });
</script>
@endsection
