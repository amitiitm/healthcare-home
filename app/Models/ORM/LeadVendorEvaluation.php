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

class LeadVendorEvaluation extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lead_vendor_evaluations';

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


}