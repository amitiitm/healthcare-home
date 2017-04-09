
<?php
$menuSelected = "feedbacks";

use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Lead Feedbacks
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
	        <span class="title">Feedbacks</span>

            <!--<button class="btn btn-danger pull-right" ng-click="deleteVendors()">Delete Vendors</button>-->
	    </div>
        <div class="ibox float-e-margins">

            <div style="display: block;" class="ibox-content">

                <table class="table table-striped table-bordered dt-responsive nowrap" id="feedbacks_table" >
                    <thead>
                    <tr>
                        <th>Feedback Date</th>
                        <th>Lead Name</th>
                        <th>Lead Mobile</th>
                        <th>Patient Name</th>
                        <th>Caregiver Name</th>
                        <th>Employee Assigned</th>
                        <th>Status</th>                       
                        <th>Remarks</th>
                        <th>Created By</th>                      
                        <th>Created At</th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div>


    </div>

</div>

<div class="new-user-button">

        <a href="<% url('admin/lead/feedback/new')%>">
        <button class="md-fab md-primary md-button md-ink-ripple" type="button" ng-transclude="" aria-label="menu">
            <i class="fa fa-plus ng-scope"></i>
        <div class="md-ripple-container"></div></button>
        </a>
    </div>
@endsection

@section('pageLevelJS')
<script type="text/javascript">
    function changeFlag(id) {
        var r = confirm("Are you sure you want to change status of this Enquiry ?");
        if (r === true) {
            $.ajax({
               type:'POST',
               url:'/api/v1/dashboard/change_enquiry_status',
               data:'enquiryId='+id+'&status='+flag_id,
               success:function(data){                    
                    alert(JSON.stringify(data));
               }
            });           
        }
    }
    
    $(function() {     
        $('#feedbacks_table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            stateDuration: 60 * 60 * 24,
            searchDelay: 1000,
            ajax: '/admin/lead/feedback_data',
            columns: [
                { data: 'feedback_date', name: 'feedback_date' },
                { data: 'lead_name', name: 'lead_name' },
                { data: 'lead_mobile', name: 'lead_mobile' },
                { data: 'patient_name', name: 'patient_name' },
                { data: 'caregiver_name', name: 'caregiver_name' },
                { data: 'employee_name', name: 'employee_name' },
                { data: 'status', name: 'status' },
                { data: 'remarks', name: 'remarks' },
                { data: 'created_by_name', name: 'created_by_name' },
                { data: 'created_at', name: 'created_at' }
            ]
        });
    });
</script>
@endsection
