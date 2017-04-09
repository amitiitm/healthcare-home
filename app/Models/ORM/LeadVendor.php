<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ORM\Lead;

/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 21/05/15
 * Time: 16:23
 */

class LeadVendor extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lead_vendors';

    use SoftDeletes;

    public function assignee(){
        return $this->belongsTo('App\Models\User','assignee_user_id');
    }
    public function assignedBy(){
        return $this->belongsTo('App\Models\User','assigned_by_user_id');
    }
    public function tasks(){
        return $this->hasMany('App\Models\ORM\LeadVendorTask','lead_vendor_id');
    }

    public static function checkDeployedVendor($vendorId){
        $response = '<span class="badge bg-warning">Not Deployed</span>';
        $lead_vendor = LeadVendor::where('assignee_user_id',$vendorId)->orderBy('id','desc')->first();
        if($lead_vendor){
           $lead = Lead::find($lead_vendor->lead_id);
           $currentStatus = $lead->statuses->last();
            if($currentStatus->slug=='started'){
                $response = '<span class="badge bg-success">Deployed</span>';
            }
        }
        return $response;
    }
}