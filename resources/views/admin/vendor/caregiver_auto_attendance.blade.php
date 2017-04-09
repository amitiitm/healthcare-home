
<?php
$menuSelected = "caregiver_auto_attendance";
?>
@extends('layouts.admin.master_new')



@section('title')
Caregiver Auto Attendance
@endsection


@section('content')
<style>
    .grid {
        width: 100%;
    }
    button.md-primary.md-fab {
    color: rgb(255,255,255);
    background-color: rgb(0,150,136);
    }
    .md-button.md-fab {
    z-index: 20;
    line-height: 56px;
    min-width: 0;
    width: 56px;
    height: 56px;
    vertical-align: middle;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,.26);
    border-radius: 50%;
    background-clip: padding-box;
    overflow: hidden;
    transition: .2s linear;
    transition-property: background-color,box-shadow;
}
</style>
<div>

    <div class="row">
<div class="page-title">
	        <span class="title">CareGiver Auto Attendance</span>

            <!--<button class="btn btn-danger pull-right" ng-click="deleteVendors()">Delete Vendors</button>-->
	    </div>
        <div class="ibox float-e-margins">

            <div style="display: block;" class="ibox-content">

                <table class="table table-striped table-bordered dt-responsive nowrap" id="caregivers_table" >
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Time Slot</th>
                        <th>Created At</th>
                        <th>Reason Phrase</th>
                        <th>Status Code</th>
                        <th>DTMF Input</th>
                        <th>Message ID</th>
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
    $(function() {     
        $('#caregivers_table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            stateDuration: 60 * 60 * 24,
            searchDelay: 1000,
            ajax: '/admin/caregiver/auto_attendance_data',
            "columnDefs": [
                { "searchable": false, "targets": 4 },
                { "searchable": false, "targets": 5 },
                { "searchable": false, "targets": 6 }
              ],
            columns: [
                { data: 'caregiver_name', name: 'caregiver_name' },
                { data: 'mobile', name: 'mobile' },
                { data: 'time_slot', name: 'time_slot' },
                { data: 'created_at', name: 'created_at' },
                { data: 'reason_phrase', name: 'reason_phrase' },
                { data: 'status_code', name: 'status_code' },
                { data: 'dtmf_input', name: 'dtmf_input' },
                { data: 'response_id', name: 'response_id' }
            ]
        });
    });
</script>
@endsection
