<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplaintLogs extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'complaints_logs';

    use SoftDeletes;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function slackEmployee(){
        return $this->belongsTo('App\Models\ORM\UserEmployee','slack_user','slack_user_id');
    }
    public function lead(){
        return $this->belongsTo('App\Models\ORM\Lead','lead_id');
    }


}