<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Created by PhpStorm.
 * User: SYMB
 * Date: 6/7/2016
 * Time: 4:46 PM
 */
class UserVendor extends Model{
    protected $table = 'user_vendors';

    public function addedByUser() {
        return $this->belongsTo('App\Models\User','added_by_user_id');
    }
    public function user() {
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function religion(){
        return $this->belongsTo('App\Models\ORM\Religion','religion_id');
    }
    public function foodType(){
        return $this->belongsTo('App\Models\ORM\FoodType','food_type_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\ORM\Service','employee_category_id');
    }
    public function source(){
        return $this->belongsTo('App\Models\ORM\VendorSource','source_id');
    }
    public function genderObject(){
        return $this->belongsTo('App\Models\ORM\Gender','gender');
    }
    public function qualification(){
        return $this->belongsTo('App\Models\ORM\Qualification','qualification_id');
    }

    public function localityObject(){
        return $this->belongsTo('App\Models\ORM\Locality','locality_id');
    }
    public function trainingNotAttendedReason(){
        return $this->belongsTo('App\Models\ORM\VendorTrainingReason','training_not_attended_reason_id');
    }

    public function locationOfWork(){
        return $this->belongsTo('App\Models\ORM\LocationZone','location_of_work');
    }
    public function shift(){
        return $this->belongsTo('App\Models\ORM\Shift','preferred_shift_id');
    }

    public function bankDetail(){
        return $this->hasOne('App\Models\ORM\VendorBankDetail','vendor_id');
    }

    public function services(){
        return $this->belongsTo('App\Models\ORM\Service','id');
    }
    public function agency(){
        return $this->belongsTo('App\Models\ORM\Agency','agency_id');
    }

    public function task(){
        return $this->belongsToMany('App\Models\ORM\Task','vendor_tasks','vendor_id','task_id')->whereNull('vendor_tasks.deleted_at')->withTimestamps();
    }
    public function uploadedDocuments(){
        return $this->hasMany('App\Models\ORM\VendorDocument','vendor_id');
    }
    public function vendorAvailabilities(){
        return $this->hasMany('App\Models\ORM\VendorAvailability','vendor_id');
    }

    use SoftDeletes;

}