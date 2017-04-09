
<?php
$menuSelected = "enquiries";

use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Followups
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
	        <span class="title">Followups</span>

            <!--<button class="btn btn-danger pull-right" ng-click="deleteVendors()">Delete Vendors</button>-->
	    </div>
        <div class="ibox float-e-margins">

            <div style="display: block;" class="ibox-content">

                <table class="table table-striped table-bordered dt-responsive nowrap" id="enquiries_table" >
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>                       
                        <th>Status</th>
                        <th>User Type</th>
                        <th>Followup Date</th>
                        <th>Created At</th>
                        <th>message</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div>


    </div>

</div>

<div class="new-user-button">

        <a href="<% url('admin/enquiry/new')%>">
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
        $('#enquiries_table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            stateDuration: 60 * 60 * 24,
            searchDelay: 2000,
            ajax: '/admin/enquiries_data',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'status', name: 'status' },
                { data: 'user_type', name: 'user_type' },
                { data: 'followup_time', name: 'followup_time' },
                { data: 'created_at', name: 'created_at' },
                { data: 'message', name: 'message' },
                { data: 'action_info', name: 'action_info' }
            ]
        });
    });
</script>
@endsection
