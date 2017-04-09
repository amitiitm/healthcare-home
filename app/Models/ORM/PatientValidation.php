<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class PatientValidation extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'patient_validations';

    use SoftDeletes;

    public function tasks(){
        return $this->belongsToMany('App\Models\ORM\Task','patient_validation_tasks','validation_id','task_id')->whereNull('patient_validation_tasks.deleted_at')->withTimestamps();
    }


}