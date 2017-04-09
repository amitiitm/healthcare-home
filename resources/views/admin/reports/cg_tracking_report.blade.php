
<?php
$menuSelected = "cg_tracking_report";

use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Care Giver Tracking Report
@endsection


@section('content')
<style>
    .grid {
        width: 100%;
    }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2>Care Giver Tracking Report</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">
                <strong>Care Giver Tracking</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">
            <form method="POST" action="/reports/cg_tracking">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="product_name">Select Date</label>
                        <input type="text" id="start_date" name="start_date" value="{!!$start_date!!}" placeholder="Enter Date" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" style="margin-top: 23px;"> 

                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<div class="row animated fadeInRight">

    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-content">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lead Name</th>                           
                            <th>CG Assigned</th>
                            <th>Flagged</th>
                            <th>Last Feedback Date</th>
                            <th>Feedback Status</th>
                            <th>Last Complaint Date</th>
                            <th>Complaint Status</th>
                            <th>Inv Amt</th>
                            <th>Inv Status</th>
                            <th>Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        <?php $statuses = ['1' => 'Pending','2' => 'Resolved','3' => 'Hold','4' => 'Replacement-Done','5' => 'Replacement-Pending','6' => 'Replacement-Closed'] ?>
                        @foreach($report_data as $data)
                        
                        <?php $feedback = \App\Models\ORM\LeadFeedback::where('lead_id',$data->lead_id)->get()->last() ?>
                        <?php $complaint = \App\Models\ORM\Complaint::where('lead_id',$data->lead_id)->get()->last() ?>
                        <?php $invoice = \App\Models\ORM\Invoice::where('lead_id',$data->lead_id)->get()->last() ?>
                        <tr>
                            <td>{!!$i!!}</td>
                            <td>{!!$data->customer_name!!}</td>
                            <td>{!!$data->caregiver_name!!}</td>
                            <td>{!!$data->is_flagged!!}</td>
                            <td>{!!$feedback ? Carbon\Carbon::parse($feedback->created_at)->toFormattedDateString() : '' !!}</td>
                            <td>{!!$feedback ? $feedback->status : '' !!}</td>
                            <td>{!!$complaint ? Carbon\Carbon::parse($complaint->created_at)->toFormattedDateString() : '' !!}</td>
                            <td>{!!$complaint ? $statuses[$complaint->status_id] : '' !!}</td>    
                            <td>{!!$invoice ? $invoice->total_amount : '' !!}</td>
                            <td>{!!$invoice ? $invoice->status : '' !!}</td>      
                            <td>{!!$data->dtmf_input!!}</td>
                        </tr>
                        <?php $i += 1 ?>
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
    $(document).ready(function(){
   $("#start_date").datepicker({
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function(selected) {
          $("#start_date").datepicker();
        }
    });

    $("#end_date").datepicker({
        minDate: 0,
        maxDate:"+60D",
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function(selected) {
           $("#end_date").datepicker();
        }
    });
});
    
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
