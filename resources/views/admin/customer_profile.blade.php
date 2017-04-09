<?php
$menuSelected = "users";
?>
@extends('layouts.admin.master')

@section('title')
    Customer Profile
@endsection

@section('content')
    <div class="page-title"><div class="title">Username</div></div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Profile Image</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center"><img src="http://52.76.28.11/pramaticare-web/public/user/profile/2?size=small" alt="profile image" class="profile-img"></div>
                    <div class="spacer-10"></div>
                    <div class="text-center"><button class="btn btn-primary">Change</button></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">User Details</div>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="form-group"><input id="username" type="text" placeholder="Update Username" class="form-control"></div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <div class="form-group"><input id="phone" type="number" placeholder="Update Phone Number" class="form-control"></div>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <div class="form-group"><input id="address" type="text" placeholder="Update Address" class="form-control"></div>
                        </div>
                        <div class="text-center"><button class="btn btn-primary">Update</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="spacer-20"></div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Change Password</div>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group"><input type="password" placeholder="Password" class="form-control"></div>
                        <div class="form-group"><input type="password" placeholder="Confirm Password" class="form-control"></div>
                        <div class="text-center"><button class="btn btn-primary">Change</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageLevelJS')

    <script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>



@endsection