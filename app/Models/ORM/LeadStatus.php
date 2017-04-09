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

class LeadStatus extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lead_status';

    use SoftDeletes;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function status(){
        return $this->belongsTo('App\Models\ORM\OperationalStatus','status_id');
    }
    public function comment(){
        return $this->belongsTo('App\Models\ORM\LeadComment','comment_id');
    }
    public function reason(){
        return $this->belongsTo('App\Models\ORM\OperationalStatusReason','reason_id');
    }
    public function lead(){
        return $this->belongsTo('App\Models\ORM\Lead','lead_id');
    }


}