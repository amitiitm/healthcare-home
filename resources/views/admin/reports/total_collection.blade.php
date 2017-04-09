
<?php
$menuSelected = "total_collection";

use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Total Collection Report
@endsection


@section('content')
<style>
    .grid {
        width: 100%;
    }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2>Total Collection Report</h2>
        <ol class="breadcrumb">
            <li>
                <a href="#">Reports</a>
            </li>
            <li class="active">
                <strong>Total Collection</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">
            <form method="POST" action="/reports/total_collection">
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
                @if (count($report_data) > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Total Amt (Cash)</th>
                            <th>Received Amt (Cash)</th>
                            <th>Remaining Amt (Cash)</th>

                            <th>Total Amt (Cheque)</th>
                            <th>Received Amt (Cheque)</th>
                            <th>Remaining Amt (Cheque)</th>

                            <th>Total Amt (Online)</th>
                            <th>Received Amt (Online)</th>
                            <th>Remaining Amt (Online)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>                        
                        @foreach($report_data as $key => $value)
                        <tr>
                            <td>{!!$i!!}</td>
                            <td>{!!Carbon\Carbon::parse($key)->toFormattedDateString()!!}</td>
                            @if (array_key_exists('1', $value))
                                <?php
                                $total_amount = $value["1"]["total_amount"];
                                $remaining_amount = $value["1"]["remaining_amount"];
                                ?>
                                <td>{!!$total_amount!!}</td>
                                <td>{!!($total_amount - $remaining_amount)!!}</td>
                                <td>{!!$remaining_amount!!}</td>
                            @else
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            @endif

                            @if (array_key_exists('2', $value))
                                <?php
                                $total_amount = $value["2"]["total_amount"];
                                $remaining_amount = $value["2"]["remaining_amount"];
                                ?>
                                <td>{!!$total_amount!!}</td>
                                <td>{!!($total_amount - $remaining_amount)!!}</td>
                                <td>{!!$remaining_amount!!}</td>
                            @else
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            @endif

                            @if (array_key_exists('3', $value))
                                <?php
                                $total_amount = $value["3"]["total_amount"];
                                $remaining_amount = $value["3"]["remaining_amount"];
                                ?>
                                <td>{!!$total_amount!!}</td>
                                <td>{!!($total_amount - $remaining_amount)!!}</td>
                                <td>{!!$remaining_amount!!}</td>
                            @else
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            @endif


                        </tr>
                        <?php $i += 1 ?>
                        @endforeach

                    </tbody>
                </table>
                @else
                <div class="alert alert-danger">
                    <strong>Oops!!!</strong> Either you have not selected any Field Executive or there is no data.
                </div>
                @endif

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
