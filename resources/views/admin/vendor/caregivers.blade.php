
<?php
$menuSelected = "employees";

use App\Models\Enums\PramatiConstants as PramatiConstants;
?>
@extends('layouts.admin.master_new')



@section('title')
Vendors
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
	        <span class="title">Care Givers</span>

            <!--<button class="btn btn-danger pull-right" ng-click="deleteVendors()">Delete Vendors</button>-->
	    </div>
        <div class="ibox float-e-margins">

            <div style="display: block;" class="ibox-content">

                <table class="table table-striped table-bordered dt-responsive nowrap" id="caregivers_table" >
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Location</th>
                        <th>Preferred Shift</th>
                        <th>Training Date</th>
                        <th>Availability</th>
                        <th>Deployed</th>
                        <th>Flag</th>
                        <th>Added By</th>
                        <th>Entry Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div>


    </div>

</div>

<div class="new-user-button">

        <a href="<% url('operation/vendor/add')%>">
        <button class="md-fab md-primary md-button md-ink-ripple" type="button" ng-transclude="" aria-label="menu">
            <i class="fa fa-plus ng-scope"></i>
        <div class="md-ripple-container"></div></button>
        </a>
    </div>
@endsection

@section('pageLevelJS')
<script type="text/javascript">
    function changeFlag(id) {
        var r = confirm("Are you sure you want to change flag of this CG ?");
        if (r === true) {
            var curr_text = $('#'+id).text();
            if(curr_text === 'Yes'){
                var flag_id = 1;
                var flag_text = 'No';
            }else{
                var flag_id = 0;
                var flag_text = 'Yes';
            }
            $.ajax({
               type:'POST',
               url:'/api/v1/vendor/changeFlag',
               data:'vendorId='+id+'&currentFlag='+flag_id,
               success:function(data){                    
                    if(curr_text === 'Yes'){
                        $('#'+id).text(flag_text);
                    }else{
                        $('#'+id).text(flag_text);
                    }
               }
            });           
        }
    }
    function deleteCG(id) {
        alert('under implementation!!!');
    }
    $(document).ready(function() {                      
        $('#caregivers_table').on('click', '.invoice_status', function (e) {
            var r = confirm("Are you sure you want to Approve this Invoice ?");
            if (r == true) {
                var invoice_id = $(this).attr('data-value');
                window.location.href = '/admin/approve_invoice/'+invoice_id;
            }
        
        });
    } );
    $(function() {     
        $('#caregivers_table').DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            stateDuration: 60 * 60 * 24,
            searchDelay: 2000,
            ajax: '/admin/caregivers_data',
            "columnDefs": [
                { "searchable": false, "targets": 2 },
                { "searchable": false, "targets": 3 }
              ],
            columns: [
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'age', name: 'age' },
                { data: 'gender', name: 'gender' },
                { data: 'location_of_work', name: 'location_of_work' },
                { data: 'preferred_shift_id', name: 'preferred_shift_id' },
                { data: 'training_attended_date', name: 'training_attended_date' },
                { data: 'availability', name: 'availability' },
                { data: 'deployed', name: 'deployed' },
                { data: 'is_flagged', name: 'is_flagged' },               
                { data: 'added_by_user_id', name: 'added_by_user_id' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action_info', name: 'action_info' }
            ]
        });
    });
</script>
@endsection
