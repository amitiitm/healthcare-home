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

class LeadApprovalEscalation extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lead_approval_escalations';

    use SoftDeletes;

    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function lead(){
        return $this->belongsTo('App\Models\ORM\Lead','lead_id');
    }


}