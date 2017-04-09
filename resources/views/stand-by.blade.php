<!-- Nursing care -->
<div class="panel-default" ng-show="status.open=='customer-detail'">
    <h1 class="old-h1">Tell us more about yourself</h1>
    <div class="spacer-20"></div>
    <div class="form-group customer-detail">
        <div class="row">
            <div class="col-md-4"><div class="text-control">Name: </div></div><div class="col-md-8"><input class="form-control" type="text" placeholder="Enter Your Name" name="name"></div>
        </div>
    </div>
    <div class="form-group customer-detail">
        <div class="row">
            <div class="col-md-4"><div class="text-control">Email: </div></div><div class="col-md-8"><input class="form-control" type="text" placeholder="Enter Your Email" name="email"></div>
        </div>
    </div>
    <div class="form-group customer-detail">
        <div class="row">
            <div class="col-md-4"><div class="text-control">Phone: </div></div><div class="col-md-8"><input class="form-control" type="text" placeholder="Enter Your Number" name="phone"></div>
        </div>
    </div>
    <div class="form-group customer-detail">
        <div class="row">
            <div class="col-md-4"><div class="text-control">Locality: </div></div><div class="col-md-8"><input class="form-control" type="text" placeholder="Enter Your Locality" name="locality"  ></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12"><small class="light-font">Please fill the above details it would help us contact you.</small></div>
    </div>

    <div class=" footer-panel">

        <div class="row">
            <div class="col-md-6 col-xs-6"><button ng-click="callMeNow()" class="btn btn-success pull-left btn-front-wizard" name="call"><i class="fa fa-phone"></i> Call Me Now</button></div>
            <div class="col-md-6 col-xs-6"><button ng-click="fillDetail()" class="btn btn-success pull-right btn-front-wizard" name="myself"><i class="fa fa-rocket"></i> Do It Myself</button></div>
        </div>

    </div>
</div>
<div class="panel-default" ng-show="status.open=='call-success'">
    <h1 class="old-h1">Thank you for submitting your requirement. </h1>
    <h2 class="subtitle-text">We have received your requirement successfully! Our team will get in touch with you soon.</h2>
    <div class=" footer-panel">
        <div class="row">
            <div class="col-xs-12 text-center"><button ng-click="backToHome()" class="btn btn-success btn-front-wizard" name="call">Back</button></div>
        </div>

    </div>
</div>
<div class="panel-default" ng-show="status.open=='patient-info'">
    <div class="Patient-block">
        <h1 class="old-h1">Patient Information</h1>
        <div class="spacer-20"></div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-control">Gender</div>
                </div>
                <div class="col-md-8">
                    <div class="btn-group btn-toggle align">
                        <label class="btn btn-success btn-grey" ng-model="radioModel" uib-btn-radio="'M'" uncheckable>Male</label>
                        <label class="btn btn-success btn-grey" ng-model="radioModel" uib-btn-radio="'F'" uncheckable>Female</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-4"><div class="text-control">Age</div></div>
                <div class="col-md-8"><input class="form-control" type="text" placeholder="Enter Your Age in Years" name="age"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-control">Weight</div>
                </div>
                <div class="col-md-8"><input class="form-control" type="text" placeholder="Enter Your Weight in Kgs" name="weight"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-control">Ailments</div>
                </div>
                <div class="col-md-8">
                    <select class="form-control">
                        <option>Ailments</option>
                        <option>Stroke</option>
                        <option>BP</option>
                        <option>ICU</option>
                        <option>High BP</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12"><small class="light-font">Please fill the above details it would help us contact you.</small></div>
        </div>
        <div class="footer-panel">
            <div class="row">
                <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-left" type="button" ng-click="gotoCustomerInformation()">Back</button></div></div>
                <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-right" type="button" ng-click="gotoRequirement()">Continue</button></div></div>
            </div>
        </div>
    </div>
</div>
<div class="panel-default" ng-show="status.open=='service-requirement'">
    <div class="Patient-block">
        <h1 class="old-h1">Requirement</h1>
        <div class="spacer-20"></div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-control">Shift Detail</div>
                </div>
                <div class="col-md-8">
                    <div class="btn-group btn-toggle shift-selector align">
                        <label class="btn btn-success btn-grey" ng-model="radioModel" uib-btn-radio="1" uncheckable>12hrs Day</label>
                        <label class="btn btn-success btn-grey" ng-model="radioModel" uib-btn-radio="2" uncheckable>12hrs Night</label>
                        <label class="btn btn-success btn-grey" ng-model="radioModel" uib-btn-radio="3" uncheckable>24hrs</label>
                    </div>
                </div>
            </div>
        </div>


        <div class="footer-panel">
            <div class="row">
                <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-left" type="button" ng-click="fillDetail()">Back</button></div></div>
                <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-right" type="button" ng-click="specialRequest()">Continue</button></div></div>
            </div>
        </div>
    </div>
</div>
<div class="panel-default" ng-show="status.open=='task-requirement'">
    <div class="Patient-block">
        <h1 class="old-h1">Task Requirement</h1>
        <div class="spacer-20"></div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Bed Store Managment</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Wheel-chair Use</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Blood Sugar Check</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Special Diet Preperation</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">ICU Monitoring</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Ventilator Patient Care</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Injection</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">IV Injection</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Vital Monitoring</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">All Hygienic Care</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Lifting and Shifting</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Blood Pressure Check</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Bedpan Usage</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">General Cooking</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Ricetube/Food-pipe</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Wound and Skin Care</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">I/V</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Ryles Tube</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">R/Tube Feeding</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-control">Others</div>
                        </div>
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" ng-model="oneAtATime">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-panel">
            <div class="row">
                <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-left" type="button" ng-click="fillDetail()">Back</button></div></div>
                <div class="col-md-6 col-xs-6"><div class="form-group"><button class=" btn-front-wizard btn btn-success pull-right" type="button" ng-click="specialRequest()">Continue</button></div></div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Nursing care ends -->

<!-- Assistive care -->
<!-- Assistive care ends -->

<!-- Physiotherapist -->
<!-- Physiotherapist ends -->

<!-- Occupational therapist -->
<!-- Occupational therapist ends -->

<!-- Speech Therapist -->
<!-- Speech Therapist ends -->