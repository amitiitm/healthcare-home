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

class Service extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'services';

    use SoftDeletes;


    public function ailments(){
        return $this->belongsToMany('App\Models\ORM\Ailment','service_ailments','service_id','ailment_id')->whereNull('service_ailments.deleted_at')->withTimestamps();
    }

    public function tasks(){
        return $this->belongsToMany('App\Models\ORM\Task','service_tasks','service_id','task_id')->whereNull('service_tasks.deleted_at')->withTimestamps();
    }


}