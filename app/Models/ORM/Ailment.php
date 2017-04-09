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

class Ailment extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ailments';

    protected $hidden = ['created_at', 'updated_at','deleted_at'];

    use SoftDeletes;


    public function tasks(){
        return $this->belongsToMany('App\Models\ORM\Task','service_ailment_tasks','ailment_id','task_id')->whereNull('service_ailment_tasks.deleted_at')->withTimestamps();
    }

}