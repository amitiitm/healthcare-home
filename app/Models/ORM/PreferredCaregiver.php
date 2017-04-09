<?php

namespace App\Models\ORM;

use Illuminate\Database\Eloquent\Model;

class PreferredCaregiver extends Model
{
    protected $fillable = ['user_id', 'lead_id', 'points','status_id'];
    
    public static function getPreferredCaregiversIds($leadId) {
        $preferred_caregiver_ids = PreferredCaregiver::where('lead_id',$leadId)
                ->where('status_id',1)
                ->orderBy('points','desc')
                ->limit(10)->get()->pluck('user_id');
        return $preferred_caregiver_ids;
    }
}
