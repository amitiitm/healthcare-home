<?php
$menuSelected = "leads";
$angularModule = 'adminModule';
?>

@extends('layouts.admin.master')

@section('title')
    Care Plan
@endsection

@section('content')
    <script>
        var leadId = '<% $model['leadId'] %>';
    </script>
    <div ng-controller="leadCarePlanCtrl" id="lead-view">
    <div id="new-lead-view">
        <div class="page-title">
            <span class="title">Care Plan</span>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Basic Info</div>
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="sub-title"><strong>Name of the client:&nbsp;</strong><span> <?php echo " ".($model['lead']['customer_name']); ?></span></div>
                            <div class="sub-title"><strong>Address:&nbsp;</strong><span><?php echo $model['lead']['address'].", ".$model['lead']['locality']['formatted_address'] ?></span></div>
                            <div class="sub-title"><strong>Contact Number:&nbsp;</strong><?php echo ($model['lead']['phone']); ?></div>
                            <div class="sub-title">
                                <strong>Ailments:&nbsp;</strong>
                                <span>
                                    <?php
									$ailemntCount = 0;

                                    if(count($model['lead']['patient']['ailments'])>0){
                                        $ailmentList = $model['lead']['patient']['ailments'];
                                        foreach($ailmentList->values() as $tempAilment){
                                            if($ailemntCount==0){
                                                echo ($tempAilment->name);
                                            }else{
                                                echo ", ".($tempAilment->name);
                                            }
                                            $ailemntCount++;
                                        }
                                    }


                                    ?>
                                </span>
                            </div>

                    </div>
                </div>
                <div class="spacer-5"></div>
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">

							<div class="table-responsive">

                            <table class="table table-bordered">

                                <tbody>
                                <?php

                                $counterSelectedTask = 0;
                                $headerDone = false;

                                foreach($model['lead_validation'] as $tempLeadValidation){
                                $anyTaskSelected = false;
	                                foreach($tempLeadValidation->tasks as $tempSingleTask){
	                                    if(isset($tempSingleTask->validation) && $tempSingleTask->validation==true){
	                                        $anyTaskSelected=true;
	                                        break;
	                                    }
	                                }
                                if($anyTaskSelected){
                                ?>
                                <tr>
                                    <th colspan=""><strong><?php echo $tempLeadValidation->categoryDto->label; ?></strong></th>
                                    <th>VALIDATION</th>
                                    <th>PRIMARY PLACEMENT</th>
                                    <th>BACKUP PLACEMENT</th>
                                    <th>
                                        PRIMARY CG EVALUATION
                                        <?php
                                        if(!$headerDone && $model['lead']->primaryVendorsAssigned && $model['lead']->primaryVendorsAssigned->count()>0){
                                            ?>
                                            <div class="text-center">
                                                <button ng-show="carePlanEditMode == 'primary-cg-evaluation'" class="btn btn-xs btn-success" ng-click="saveData()"><i class="fa fa-save"></i> Save</button>
                                                <button ng-show="carePlanEditMode == 'primary-cg-evaluation'" class="btn btn-xs btn-default" ng-click="cancelCarePlanEdit()"><i class="fa fa-close"></i></button>
                                                <button ng-show="carePlanEditMode == ''" class="btn btn-xs btn-success" ng-click="markEvaluation('primary-cg-evaluation')">Mark Evaluation</button>
                                            </div>
                                            <?php
                                        }
                                        ?>


                                    </th>
                                    <th>
                                        BACKUP CG EVALUATION
                                        <?php
                                        if(!$headerDone && $model['lead']->vendorsAssigned && $model['lead']->vendorsAssigned->count()>0){
                                            ?>
                                            <div class="text-center">
	                                            <button ng-show="carePlanEditMode == 'backup-cg-evaluation'" class="btn btn-xs btn-success" ng-click="saveData()"><i class="fa fa-save"></i> Save</button>
	                                            <button ng-show="carePlanEditMode == 'backup-cg-evaluation'" class="btn btn-xs btn-default" ng-click="cancelCarePlanEdit()"><i class="fa fa-close"></i></button>
	                                            <button ng-show="carePlanEditMode == ''" class="btn btn-xs btn-success" ng-click="markEvaluation('backup-cg-evaluation')">Mark Evaluation</button>
	                                        </div>
                                            <?php
                                        }
                                        ?>
                                    </th>
                                    <th>
                                        TRAINING DONE ON
										<?php
                                        if(!$headerDone && $model['lead']->primaryVendorsAssigned && $model['lead']->primaryVendorsAssigned->count()>0){
                                            ?>
                                             <div class="text-center">
                                                <button ng-show="carePlanEditMode == 'cg-training-evaluation'" class="btn btn-xs btn-success" ng-click="saveData()"><i class="fa fa-save"></i> Save</button>
                                                <button ng-show="carePlanEditMode == 'cg-training-evaluation'" class="btn btn-xs btn-default" ng-click="cancelCarePlanEdit()"><i class="fa fa-close"></i></button>
                                                <button ng-show="carePlanEditMode == ''" class="btn btn-xs btn-success" ng-click="markEvaluation('cg-training-evaluation')">Mark Training</button>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </th>
                                    <th>
                                        CUSTOMER SIGN OFF
                                        <?php
	                                    if(!$headerDone && $model['lead']->primaryVendorsAssigned && $model['lead']->primaryVendorsAssigned->count()>0){
	                                        ?>
	                                        <div class="text-center">
                                                <button ng-show="carePlanEditMode == 'cg-sign-off-evaluation'" class="btn btn-xs btn-success" ng-click="saveData()"><i class="fa fa-save"></i> Save</button>
                                                <button ng-show="carePlanEditMode == 'cg-sign-off-evaluation'" class="btn btn-xs btn-default" ng-click="cancelCarePlanEdit()"><i class="fa fa-close"></i></button>
                                                <button ng-show="carePlanEditMode == ''" class="btn btn-xs btn-success" ng-click="markEvaluation('cg-sign-off-evaluation')">Mark Sign Off</button>
                                            </div>
	                                        <?php
	                                    }
	                                    ?>
                                    </th>
                                </tr>
                                <?php
                                $headerDone = true;

                                foreach($tempLeadValidation->tasks as $tempSingleTask){
                                //d($tempSingleTask);
                                if(isset($tempSingleTask->validation) && $tempSingleTask->validation==true){
                                ?>

                                <tr>
                                    <td><?php echo  $tempSingleTask->taskInfo->label; ?></td>
                                    <td class="text-center">
                                        <?php
                                        if(isset($tempSingleTask->validation) && $tempSingleTask->validation==true){
                                            echo '<i class="fa fa-check"></i>';
                                            $counterSelectedTask++;
                                        }else{
                                            echo '<i class="fa fa-times"></i>';
                                        }
                                        ?>

                                    </td>

                                    <td  class="text-center">

                                        <?php
                                        if(isset($tempSingleTask->primarySourcing) && $tempSingleTask->primarySourcing==1){
                                            echo '<i class="fa fa-check"></i>';

                                        }else if (isset($tempSingleTask->primarySourcing) && $tempSingleTask->primarySourcing==-1){
                                            echo '<i class="fa fa-times"></i>';
                                        }else{

                                        }
                                        ?>

                                    </td>
                                    <td  class="text-center">

                                        <?php
                                        if(isset($tempSingleTask->backUpSourcing) && $tempSingleTask->backUpSourcing==1){
                                            echo '<i class="fa fa-check"></i>';

                                        }else if (isset($tempSingleTask->backUpSourcing) && $tempSingleTask->backUpSourcing==-1){
                                            echo '<i class="fa fa-times"></i>';
                                        }else{

                                        }
                                        ?>

                                    </td>

                                    <td class="text-center">
                                        <div ng-show="carePlanEditMode != 'primary-cg-evaluation'">
                                            <?php
                                            if(isset($tempSingleTask->primaryCGEvaluationByQc) && $tempSingleTask->primaryCGEvaluationByQc==1){
                                                echo '<i class="fa fa-check"></i>';

                                            }else if (isset($tempSingleTask->primaryCGEvaluationByQc) && $tempSingleTask->primaryCGEvaluationByQc==-1){
                                                echo '<i class="fa fa-times"></i>';
                                            }else{

                                            }
                                            ?>
                                        </div>
                                        <div class="primary-cg-evaluation-checkbox" ng-show="carePlanEditMode == 'primary-cg-evaluation'" >
                                            <input type="checkbox" value="true" name="primary-cg-evaluation-checkbox<?php echo $tempSingleTask->taskInfo->id ?>"  <?php echo (isset($tempSingleTask->primaryCGEvaluationByQc) && $tempSingleTask->primaryCGEvaluationByQc==1)?"checked":''; ?> />
                                            <div style="display: none" class="task-info"><?php echo $tempSingleTask->taskInfo->id ?></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div ng-show="carePlanEditMode != 'backup-cg-evaluation'">
	                                        <?php
	                                        if(isset($tempSingleTask->backUpCGEvaluationByQc) && $tempSingleTask->backUpCGEvaluationByQc==1){
	                                            echo '<i class="fa fa-check"></i>';

	                                        }else if (isset($tempSingleTask->backUpCGEvaluationByQc) && $tempSingleTask->backUpCGEvaluationByQc==-1){
	                                            echo '<i class="fa fa-times"></i>';
	                                        }else{

	                                        }
	                                        ?>
	                                    </div>
                                        <div class="text-center backup-cg-evaluation-checkbox" ng-show="carePlanEditMode == 'backup-cg-evaluation'" >
                                            <input type="checkbox" value="true" name="primary-cg-evaluation-checkbox<?php echo $tempSingleTask->taskInfo->id ?>" <?php echo (isset($tempSingleTask->backUpCGEvaluationByQc) && $tempSingleTask->backUpCGEvaluationByQc==1)?"checked":''; ?>  />
                                            <div style="display: none" class="task-info"><?php echo $tempSingleTask->taskInfo->id ?></div>
                                        </div>
                                    </td>
                                    <td class="text-center">

                                        <div ng-show="carePlanEditMode != 'cg-training-evaluation'">
                                            <?php
                                            if(isset($tempSingleTask->cgTrainingDone) && $tempSingleTask->cgTrainingDone==1){
                                                echo '<i class="fa fa-check"></i>';

                                            }else if (isset($tempSingleTask->cgTrainingDone) && $tempSingleTask->cgTrainingDone==-1){
                                                echo '<i class="fa fa-times"></i>';
                                            }else{

                                            }
                                            ?>
                                        </div>
                                        <div class="text-center cg-training-evaluation-checkbox" ng-show="carePlanEditMode == 'cg-training-evaluation'" >
                                            <input type="checkbox" value="true" name="cg-training-evaluation-checkbox<?php echo $tempSingleTask->taskInfo->id ?>" <?php echo (isset($tempSingleTask->cgTrainingDone) && $tempSingleTask->cgTrainingDone==1)?"checked":''; ?>  />
                                            <div style="display: none" class="task-info"><?php echo $tempSingleTask->taskInfo->id ?></div>
                                        </div>

                                    </td>
                                    <td class="text-center">

                                        <div ng-show="carePlanEditMode != 'cg-sign-off-evaluation'">
                                            <?php
                                            if(isset($tempSingleTask->finalEvaluation) && $tempSingleTask->finalEvaluation==1){
                                                echo '<i class="fa fa-check"></i>';

                                            }else if (isset($tempSingleTask->finalEvaluation) && $tempSingleTask->finalEvaluation==-1){
                                                echo '<i class="fa fa-times"></i>';
                                            }else{

                                            }
                                            ?>
                                        </div>
                                        <div class="text-center cg-sign-off-evaluation-checkbox" ng-show="carePlanEditMode == 'cg-sign-off-evaluation'" >
                                            <input type="checkbox" value="true" name="cg-sign-off-evaluation-checkbox<?php echo $tempSingleTask->taskInfo->id ?>" <?php echo (isset($tempSingleTask->finalEvaluation) && $tempSingleTask->finalEvaluation==1)?"checked":''; ?>  />
                                            <div style="display: none" class="task-info"><?php echo $tempSingleTask->taskInfo->id ?></div>
                                        </div>


                                    </td>
                                </tr>
                                <?php
                                }
                                }
                                }
                                }
                                if($counterSelectedTask==0){
                                    echo "<tr><td class='text-center'><h4>No task selected</h4></td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                <div class="spacer-5"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Primary CG Assigned</div>
                        </div>
                    </div>
                    <div class="widget-content">

                        <div class="user-card">
                            <div class="row">
                                <div class="col-md-12 ng-hide" ng-show="!lead.primaryVendorAssigned.id">
                                    <div class="card-body">
                                        <div class="sub-title">No Care-Giver Assigned</div>
                                    </div>
                                </div>
                                <div class="col-md-12 ng-hide" ng-show="lead.primaryVendorAssigned.id">
                                    <div class="card-body">
                                        <div class="sub-title"><strong>Name:&nbsp;</strong><a href="#"><span ng-bind="lead.primaryVendorAssigned.name"></span></a></div>
                                        <div class="sub-title"><strong>Phone:&nbsp;</strong><a ng-href="tel:{{lead.primaryVendorAssigned.phone}}"><span ng-bind="lead.primaryVendorAssigned.phone"></span></a> </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Backup CG Assigned</div>
                        </div>
                    </div>
                    <div class="widget-content">

                        <div class="user-card">
                            <div class="row">
                                <div class="col-md-12 ng-hide" ng-show="!lead.vendorAssigned.id">
                                    <div class="card-body">
                                        <div class="sub-title">No Care-Giver Assigned</div>
                                    </div>
                                </div>
                                <div class="col-md-12 ng-hide" ng-show="lead.vendorAssigned.id">
                                    <div class="card-body">
                                        <div class="sub-title"><strong>Name:&nbsp;</strong><a href="#"><span ng-bind="lead.vendorAssigned.name"></span></a></div>
                                        <div class="sub-title"><strong>Phone:&nbsp;</strong><a ng-href="tel:{{lead.vendorAssigned.phone}}"><span ng-bind="lead.vendorAssigned.phone"></span></a> </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="title">Patient Information</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Name of patient
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.name"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Gender
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.gender.label"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Age
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.age" ng-show="lead.patient.age>0"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Weight
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.weight"  ng-show="lead.patient.weight>0"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Ailment
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-repeat="ailment in lead.patient.ailments" class="badge ailment bg-warning" ng-bind="ailment.name"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        Shift Required
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <span ng-bind="lead.patient.shiftRequired.label"></span>
                                    </div>
                                </div>
                                <div ng-show="showMoreEmployeeDetail">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            On Equipment Support
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="lead.patient.isOnEquipmentSupport">Yes</span>
                                            <span ng-show="!lead.patient.isOnEquipmentSupport">No</span>
                                        </div>
                                    </div>
                                    <div class="row" ng-show="lead.patient.isOnEquipmentSupport">
                                        <div class="col-md-6 col-sm-6">
                                            Equipment
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-bind="lead.patient.equipment.name"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Mobility
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-bind="lead.patient.mobility.label"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Illness
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-bind="lead.patient.illness"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Physical Condition
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-bind="lead.patient.physicalCondition"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Morning Wakeup Time
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="lead.patient.morningWakeUpTime!='00:00:00'" ng-bind="lead.patient.morningWakeUpTime"></span>
                                            <span ng-show="lead.patient.morningWakeUpTime=='00:00:00'">Not Available</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Walking Time
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="lead.patient.walkTiming!='00:00:00'" ng-bind="lead.patient.walkTiming"></span>
                                            <span ng-show="lead.patient.walkTiming=='00:00:00'">Not Available</span>
                                        </div>
                                    </div>
                                    <div class="row ng-hide">
                                        <div class="col-md-6 col-sm-6">
                                            Walking Location
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-bind="lead.patient.walkLocation"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Breakfast Time
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="lead.patient.breakfastTime!='00:00:00'" ng-bind="lead.patient.breakfastTime"></span>
                                            <span ng-show="lead.patient.breakfastTime=='00:00:00'">Not Available</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Lunch Time
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="lead.patient.lunchTime!='00:00:00'" ng-bind="lead.patient.lunchTime"></span>
                                            <span ng-show="lead.patient.lunchTime=='00:00:00'">Not Available</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Dinner Time
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="lead.patient.dinnerTime!='00:00:00'" ng-bind="lead.patient.dinnerTime"></span>
                                            <span ng-show="lead.patient.dinnerTime=='00:00:00'">Not Available</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Religion Preference
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.religionPreference">No</span>
                                            <span ng-show="lead.patient.religionPreference" ng-bind="lead.patient.religionPreferred.label"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Age Preference
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.agePreferece">No</span>
                                            <span ng-show="lead.patient.agePreferece" ng-bind="lead.patient.agePreferred.label"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Food Preference
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.foodPreference">No</span>
                                            <span ng-show="lead.patient.foodPreference" ng-bind="lead.patient.foodPreferred.label"></span>
                                        </div>
                                    </div>
                                    <div class="row ng-hide">
                                        <div class="col-md-6 col-sm-6">
                                            Gender Preference
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.genderPreference">No</span>
                                            <span ng-show="lead.patient.genderPreference">
                                                <span ng-show="lead.patient.genderPreferred=='F'">Female</span>
                                                <span ng-show="lead.patient.genderPreferred=='M'">Male</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row ng-hide">
                                        <div class="col-md-6 col-sm-6">
                                            Language Preference
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.languagePreference">No</span>
                                            <span ng-show="lead.patient.languagePreference" ng-bind="lead.patient.languagePreferred.label"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Doctor
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.doctor">None</span>
                                            <span ng-show="lead.patient.doctor" ng-bind="lead.patient.doctor.name"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            Hospital
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <span ng-show="!lead.patient.hospital">None</span>
                                            <span ng-show="lead.patient.hospital" ng-bind="lead.patient.hospital.name"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-link no-margin" ng-click="toggleEmployeeDetail()">
                                        <span ng-show="!showMoreEmployeeDetail">Show More</span>
                                        <span ng-show="showMoreEmployeeDetail">Show Less</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<script type="text/ng-template" id="carePlanCheckList.html">
            <div class="modal-header">
                <h3 class="modal-title">CG Evaluation: <span></span></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div ui-grid="carePlanGridOptions" ui-grid-edit class="grid"></div>

                    </div>
                </div>
                <div class="col-md-"></div>
            </div>
            <div class="modal-footer">
                <div class="pull-left">
                    <input ng-model="checkAll" type="checkbox" /> Select All
                </div>
                 <button class="btn btn-success" type="button" ng-disabled="!isAssignable()" ng-click="submitBriefing()">Submit Briefing</button>
                 <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
            </div>
        </script>

@endsection

@section('pageLevelJS')

    <script type="text/javascript" src="<% asset('static/js/services/adminServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/services/operationServices.js')%>"></script>
    <script type="text/javascript" src="<% asset('static/js/admin/script.js')%>"></script>


</div>
        </div>
    </div>
    </div>
@endsection