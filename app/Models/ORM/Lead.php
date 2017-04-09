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

class Lead extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'leads';

    use SoftDeletes;

    public function enquiry(){
        return $this->belongsTo('App\Models\ORM\Enquiry','enquiry_id');
    }
    public function service(){
        return $this->belongsTo('App\Models\ORM\Service','service_id');
    }
    public function patient(){
        return $this->hasOne('App\Models\ORM\Patient','lead_id');
    }
    public function employeesAssigned(){
        return $this->belongsToMany('App\Models\User','lead_employees','lead_id','assignee_user_id')
            ->whereNull('lead_employees.deleted_at')
            ->withTimestamps()->orderBy('lead_employees.created_at','asc')->withPivot('lead_id');
    }
    public function primaryVendorsAssigned(){
        return $this->belongsToMany('App\Models\User','lead_vendors','lead_id','assignee_user_id')
            ->whereNull('lead_vendors.deleted_at')
            ->where('is_primary','=',true)
            ->withTimestamps()->orderBy('lead_vendors.created_at','asc');
    }
    public function vendorsAssigned(){
        return $this->belongsToMany('App\Models\User','lead_vendors','lead_id','assignee_user_id')
            ->whereNull('lead_vendors.deleted_at')
            ->where('is_primary','=',false)
            ->withTimestamps()->orderBy('lead_vendors.created_at','asc');
    }
    public function qcAssigned(){
        return $this->belongsToMany('App\Models\User','lead_qc_assignments','lead_id','assignee_user_id')
            ->whereNull('lead_qc_assignments.deleted_at')
            ->withTimestamps()->orderBy('lead_qc_assignments.created_at','asc');
    }
    public function fieldAssigned(){
        return $this->belongsToMany('App\Models\User','lead_field_assignments','lead_id','assignee_user_id')
            ->whereNull('lead_field_assignments.deleted_at')
            ->withTimestamps()->orderBy('lead_field_assignments.created_at','asc');
    }
    public function statuses(){
        return $this->belongsToMany('App\Models\ORM\OperationalStatus','lead_status','lead_id','status_id')
            ->whereNull('lead_status.deleted_at')
            ->withTimestamps()->orderBy('lead_status.created_at','asc');
    }
    public function approvalStatus(){
        return $this->belongsToMany('App\Models\ORM\OperationalStatus','lead_status','lead_id','status_id')
            ->whereNull('lead_status.deleted_at')->where('operations_statuses.slug','=','lead-approved')
            ->withTimestamps()->orderBy('lead_status.created_at','asc');
    }
    public function closedStatus(){
        return $this->hasOne('App\Models\ORM\OperationalStatus','lead_status','lead_id','status_id')
            ->whereNull('lead_status.deleted_at')->where('operations_statuses.slug','=','closed')
            ->withTimestamps()->orderBy('lead_status.created_at','asc');
    }
    public function leadSource(){
        return $this->belongsTo('App\Models\ORM\LeadSource','source_id');
    }
    public function leadReference(){
        return $this->belongsTo('App\Models\ORM\LeadReference','reference_id');
    }
    public function paymentType(){
        return $this->belongsTo('App\Models\ORM\PaymentType','payment_type_id');
    }
    public function paymentPeriod(){
        return $this->belongsTo('App\Models\ORM\PaymentPeriod','payment_period_id');
    }
    public function paymentMode(){
        return $this->belongsTo('App\Models\ORM\PaymentMode','payment_mode_id');
    }
    public function locality(){
        return $this->belongsTo('App\Models\ORM\Locality','locality_id');
    }
    public function userCreated(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function deviceTokens(){
        return $this->hasMany('App\Models\ORM\DeviceToken','user_id');
    }
    public function patientAilment(){
        return $this->hasOne('App\Models\ORM\PatientAilment','ailment_id');
    }
    public function prices(){
        return $this->hasMany('App\Models\ORM\LeadPrice','lead_id');
    }
    public function approvalEscalations(){
        return $this->hasMany('App\Models\ORM\LeadApprovalEscalation','lead_id');
    }
    public function patientEquipment(){
        return $this->hasOne('App\Models\ORM\patientEquipment','equipment_id');
    }
}