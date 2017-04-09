<?php


$menuSelected = "employees";
$angularModule = 'employeeModule';

?>
@extends('......layouts.admin.master')



@section('title')
    Dashboard
@endsection

@section('content')

   <script>
           var employeeId = '<?php echo $employeeId; ?>';
   	</script>
    <style>
        .grid {
            width: 100%;
        }
    </style>
    <div ng-controller="EmployeeEditController" id="lead-view">
        <div class="page-title">
            <span class="title">Employee Edit</span>
        </div>
        <div class="card profile">

            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Basic Information</a></li>
                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Departments</a></li>
                    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Access Management</a></li>
                    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Authentication</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row topPic">
                            <div class="col-md-4 text-center">
                                <div >
                                    <img  class="img-responsive width-200" style="margin: auto" ng-src="{{userData.basic.userImage}}">
                                </div>

                                <div class="margin-top-10">
                                    <button type="button" class="btn btn-success ng-hide" ng-click="uploadDP()">Upload Photo</button>
                                </div>

								<div class="margin-top-10"></div>
								<div ng-show="userData.basic.slackUsername">
									<i class="fa fa-slack"></i> <span>@</span><span ng-bind="userData.basic.slackUsername"></span>
								</div>
                                <button  ng-show="!userData.basic.slackUsername && userData.basic.slackInvitationSendAt==null" type="button" class="btn btn-success" ng-click="generateSlackUser()">Generate Slack Username</button>
                                <div  ng-show="!userData.basic.slackUsername && userData.basic.slackInvitationSendAt!=null">Slack Invitation Send At: <span ng-bind="userData.basic.slackInvitationSendAt"></span></div>
                                <button  ng-show="!userData.basic.slackUsername && userData.basic.slackInvitationSendAt!=null" type="button" class="btn btn-success" ng-click="generateSlackUser()">Resend Slack Invitation</button>
                            </div>
                            <div class="col-md-8 description ">

                                    <div class="row">
                                        <div class="col-md-4 "><label>Name</label></div>
                                        <div class="col-md-6">
                                            <form name="frm1" data-toggle="validator">
                                            <input type="text" class="form-control" id="inputName" placeholder="" name="name" ng-model="userData.basic.name" required>
                                                <span ng-show="frm1.name.$touched && frm1.name.$invalid">  Please fill your name!</span>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="margin-top-5"></div>

                                    <div class="row">
                                        <div class="col-md-4"><label>Email</label></div>
                                        <div class="col-md-6">
                                            <form name="frm2" data-toggle="validator">
                                                <input type="email" placeholder="abc@gmail.com" name="email" ng-model="userData.basic.email" class="form-control"  required>
                                                <span ng-show="frm2.email.$touched && frm2.email.$invalid">  Please fill your email!</span>
                                            </form>
                                       </div>
                                    </div>
                                    <div class="margin-top-5"></div>

                                    <div class="row">
                                        <div class="col-md-4"><label>Phone</label></div>
                                        <div class="col-md-6">
                                            <form name="frm3" data-toggle="validator"><input type="text" placeholder="9999999999" name="mobile" ng-model="userData.basic.phone" ng-pattern="phoneNumber" class="form-control"  required>
                                                <span ng-show="frm3.mobile.$touched && frm3.mobile.$invalid">  Please fill your mobile number </span>
                                                <span class="error" ng-show="frm3.mobile.$error.pattern">of 10 digit in Numeric only!</span>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="margin-top-5"></div>

                                    <div class="row">
                                        <div class="col-md-4"><label>Address</label></div>
                                        <div class="col-md-6">
                                            <textarea class="editAddress" type="text" class="form-control" ng-model="userData.basic.address" placeholder="Indrapuram Ghaziabad"></textarea>
                                        </div>
                                    </div>

									<div class="margin-top-5"></div>

                                    <div class="row">
                                        <div class="col-md-6 col-md-offset-4">
                                                <a class="btn btn-success" href="#" role="button" ng-click="updateBasicInformation()" ng-disabled="!userData.basic.name || !userData.basic.email || !userData.basic.phone">Update Basic Information</a>

                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<!--    <script type="text/ng-template" id="uploadPrescriptionModalTemplate.html">
        <div class="modal-header">
            <h3 class="modal-title">Upload Employee Photosssss</h3>
        </div>
        <div class="modal-body">
            <div class="text-center">
                <img src="{{daycare.logoUrl}}" class="img-responsive" />
            </div>
            <div upload-dialog model="prescriptionUploadModel"></div>
            <div class="margin-top-10 text-center">
                <button class="btn btn-sm btn-info" ng-click="changeLogo()">Upload Logo</button>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success" type="button" ng-click="uploadPic(picFile)">Update</button>
            <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
        </div>
    </script>-->


@endsection

@section('pageLevelJS')

    <script type="text/javascript" src="<% asset('static/vendors/lodash/lodash.underscore.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/vendors/angular-simple-logger/angular-simple-logger.min.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/vendors/angular-google-maps/angular-google-maps.js')%>"></script>

    <script type="text/javascript" src="<% asset('static/js/services/userServices.js')%>"></script>


    <script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>

    <script type="text/javascript" src="<% asset('static/js/services/employeeServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/admin/employeescript.js')%>"></script>



@endsection