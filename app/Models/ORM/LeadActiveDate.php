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

class LeadActiveDate extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lead_active_dates';

    use SoftDeletes;

    public function lead(){
        return $this->belongsTo('App\Models\ORM\Lead','lead_id');
    }

    public function vendorAttendance(){
        return $this->hasOne('App\Models\ORM\LeadVendorAttendance','date','active_date');
    }

    public function customerVendorAttendance(){
        return $this->hasOne('App\Models\ORM\CustomerVendorAttendance','attendance_date','active_date');
    }
}