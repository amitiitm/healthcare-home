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

class Patient extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'patients';

    use SoftDeletes;

    public function genderItem(){
        return $this->belongsTo('App\Models\ORM\Gender','gender');
    }

    public function shift(){
        return $this->belongsTo('App\Models\ORM\Shift','shift_id');
    }
    public function religionPreferred(){
        return $this->belongsTo('App\Models\ORM\Religion','religion_preferred');
    }
    public function languagePreferred(){
        return $this->belongsTo('App\Models\ORM\Language','language_preferred');
    }
    public function foodPreferred(){
        return $this->belongsTo('App\Models\ORM\FoodType','food_preferred');
    }
    public function agePreferred(){
        return $this->belongsTo('App\Models\ORM\AgeRange','age_preferred');
    }
    public function genderPreferred(){
        return $this->belongsTo('App\Models\ORM\Gender','gender_preferred');
    }
    public function equipment(){
        return $this->belongsTo('App\Models\ORM\Equipment','equipment_id');
    }
    public function physiotherapy(){
        return $this->hasOne('App\Models\ORM\PatientPhysiotherapy','patient_id');
    }
    public function mobilityItem(){
        return $this->belongsTo('App\Models\ORM\Mobility','mobility_id');
    }
    public function ailments(){
        return $this->belongsToMany('App\Models\ORM\Ailment','patient_ailments','patient_id','ailment_id')->whereNull('patient_ailments.deleted_at')->withTimestamps();
    }
    public function tasks(){
        return $this->belongsToMany('App\Models\ORM\Task','patient_tasks','patient_id','task_id')->whereNull('patient_tasks.deleted_at')->withTimestamps();
    }

    public function equipments(){
        return $this->belongsToMany('App\Models\ORM\Equipment','patient_equipments','patient_id','equipment_id')->whereNull('patient_equipments.deleted_at')->withTimestamps();

    }




}