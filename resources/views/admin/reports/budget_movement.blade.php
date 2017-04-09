
<?php
$menuSelected = "budget_movement";

use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Budget Movement Report
@endsection


@section('content')
<style>
    .grid {
        width: 100%;
    }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2>Budget Movement Report</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">
                <strong>Budget Movement</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">
            <form method="POST" action="/reports/budget_movement">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="product_name">Start Date</label>
                        <input type="text" id="start_date" name="start_date" value="{!!$start_date!!}" placeholder="Enter Start Date" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="price">End Date</label>
                        <input type="text" id="end_date" name="end_date" value="{!!$end_date!!}" placeholder="Enter End Date" class="form-control">
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
                            <th>Date</th>
                            <th>Total Amount (In Rs.)</th>
                            <th>Received Amount (In Rs.)</th>
                            <th>Remaining Amount (In Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        @foreach($report_data as $data)
                        <tr>
                            <td>{!!$i!!}</td>
                            <td>{!!Carbon\Carbon::parse($data->created_at)->toFormattedDateString()!!}</td>
                            <td>{!!$data->total_amount!!}</td>
                            <td>{!!$data->paid_amt!!}</td>
                            <td>{!!($data->total_amount - $data->paid_amt)!!}</td>
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
        minDate: "-90D",
        maxDate: "0",
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
