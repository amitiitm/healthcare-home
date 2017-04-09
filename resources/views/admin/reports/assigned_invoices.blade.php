
<?php
$menuSelected = "assigned_invoices";

use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Dashboard
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

                <table class="table table-striped table-bordered dt-responsive nowrap" id="data-table-grid" >
                    <thead>
                        @if($referer && $referer == 'online_invoice')
                        <tr><a href="/admin/assigned_invoices" class="badge">Assigned Invoices</a></tr>
                        @else
                        <tr><a href="/admin/online_assigned_invoices" class="badge bg-success">Update Online Invoice Payments</a></tr>
                        @endif
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
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)

                        <tr>
                            <td>{!!$invoice->order_number!!}</td>
                            <td>{!!$invoice->invoice_number!!}</td>
                            <td>{!! $invoice->created_at //echo(date_format($invoice->created_at,'M N, Y')!!}</td>
                            <td>{!!$invoice->customer_name!!}</td>
                            <td>{!!$invoice->email!!}</td>
                            <td>{!!$invoice->phone!!}</td>
                            <td>{!!$invoice->address!!}</td>
                            <td>{!!$invoice->price_per_day!!}</td>
                            <td>{!!$invoice->total_amount!!}</td>                            
                            <td style="color:#3BAA3B;">{!!$invoice->status!!}</td>                           
                            <td>
                                <a href="/admin/edit_invoice_payment/{!!$invoice->id!!}" class="edit_link" data-value='{!!$invoice->id!!}'>Update Payment</a>
                                <!--@if($invoice->date_of_collection === null || $invoice->date_of_collection === '' || $invoice->date_of_collection == '0000-00-00')
                                    <a href="/admin/edit_invoice_payment/{!!$invoice->id!!}" class="edit_link" data-value='{!!$invoice->id!!}'>Update Payment</a>
                                @else
                                    Updated
                                @endif-->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>


    </div>

</div>


@endsection

@section('pageLevelJS')
<script type="text/javascript">
    $('.invoice_status').on('change', function () {
        var status = this.value;
        if (status == "{!!strtolower(PramatiConstants::APPROVED_INVOICE_STATUS)!!}") {
            var txt;
            var r = confirm("Are you sure you want to Approve this Invoice ?");
            if (r == true) {
                var invoice_id = $(this).attr('data-value');
                window.location.href = '/admin/approve_invoice/' + invoice_id;
            }
        }
    });
</script>
@endsection
