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

class CustomerStatusRequest extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customer_lead_status_requests';


    public function status(){
        return $this->belongsTo('App\Models\ORM\OperationalStatus','status_id');
    }


    public function reason(){
        return $this->belongsTo('App\Models\ORM\OperationalStatusReason','reason_id');
    }

    public function issue(){
        return $this->belongsTo('App\Models\ORM\ProjectClosureIssue','reason_id');
    }

}