<?php

namespace App\Models\ORM;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by YBT.
 * User: Vineet Kumar
 */
class CaregiverAutoAttendance extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'caregiver_auto_attendances';
    protected $fillable = [
        'user_id','lead_id',
        'caregiver_name',
        'mobile',
        'status_code',
        'reason_phrase',
        'response',
        'dtmf_input',
        'response_id',
        'time_slot',
        'created_at',
        'updated_at'
    ];
    
    public static function getCaregiverAutoAttendanceData(){
        $attendances = CaregiverAutoAttendance::get();
        return $attendances;
    }

}
