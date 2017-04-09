<?php
namespace App\Models\DTO\Careplan;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class PatientCarePlanTaskDTO{

    public $taskInfo;

    public $validation;

    public $primarySourcing;
    public $backUpSourcing;

   /* public $qcBriefing;*/

    public $primaryCGEvaluationByQc;
    public $backUpCGEvaluationByQc;

    public $cgTrainingDone;

    public $finalEvaluation;


}