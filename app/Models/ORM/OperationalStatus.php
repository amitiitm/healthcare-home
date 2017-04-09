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

class OperationalStatus extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'operations_statuses';

    use SoftDeletes;

    public function reasons(){
        return $this->hasMany('App\Models\ORM\OperationalStatusReason','status_id');
    }
}