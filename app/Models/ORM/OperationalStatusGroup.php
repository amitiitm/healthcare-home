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

class OperationalStatusGroup extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'operations_status_groups';

    use SoftDeletes;

    public function statuses(){
        return $this->hasMany('App\Models\ORM\OperationalStatus','group_id');
    }


}