<?php

namespace App\Models\ORM;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ORM\Lead;
use App\Models\ORM\Patient;
use App\Models\ORM\LeadVendor;
use App\Models\ORM\LeadEmployee;
use Cache;
/**
 * Created by YBT.
 * User: Vineet Kumar
 */
class LeadFeedback extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lead_feedbacks';
    protected $fillable = [
        'lead_id','lead_name','lead_mobile',
        'patient_id','patient_name',
        'caregiver_id','caregiver_name',
        'employee_id','employee_name',
        'created_by','created_by_name',
        'feedback_date',
        'status',
        'remarks',
        'created_at',
        'updated_at'
    ];
    
    public static function getLeadsForDropdown(){
        $leads_array = Cache::remember('leads_for_dropdown', 30, function () {
            $leads_array = ['' => '-Select Lead-'];
            $leads = Lead::whereNull('deleted_at')
                ->select('id','customer_name','customer_last_name','phone')
                ->orderBy('customer_name')
                ->get();
            foreach($leads as $lead){
               $lead_name = $lead->customer_name. ' '.$lead->customer_last_name .'('.$lead->phone.')';
               $leads_array[$lead->id] = str_limit($lead_name,50); 
            }
            return $leads_array;
        });
        return $leads_array;
    }
    
    public static function getLeadDetails($leadId){
       $detail_array = [];
       $lead = Lead::where('id',$leadId)->get()->first();
       $patient = Patient::where('lead_id',$lead->id)->get()->first();
       $caregiver = LeadVendor::whereNull('lead_vendors.deleted_at')
                    ->where('is_primary','=',true)
                    ->where('lead_id',$lead->id)
                    ->join('users', 'users.id', '=', 'lead_vendors.assignee_user_id')
                    ->select('assignee_user_id','name')
                    ->get()->last();
       $employee = LeadEmployee::whereNull('lead_employees.deleted_at')
                    ->where('lead_id',$lead->id)
                    ->join('users', 'users.id', '=', 'lead_employees.assignee_user_id')
                    ->select('assignee_user_id','name')
                    ->get()->last();
       $detail_array['lead_id'] = $lead->id;
       $detail_array['lead_name'] = $lead->customer_name. ' '.$lead->customer_last_name;
       $detail_array['lead_mobile'] = $lead->phone;
       $detail_array['patient_id'] = $patient ? $patient->id : '';
       $detail_array['patient_name'] = $patient ? $patient->name : '';
       $detail_array['caregiver_id'] = $caregiver ? $caregiver->assignee_user_id : '';
       $detail_array['caregiver_name'] = $caregiver ? $caregiver->name : '';
       $detail_array['employee_id'] = $employee ? $employee->assignee_user_id : '';
       $detail_array['employee_name'] = $employee ? $employee->name : '';
       return $detail_array;
    }

    public static function getFeedback($leadId) {
       return self::where('lead_id',$leadId)->get(); 
    }
    
}
