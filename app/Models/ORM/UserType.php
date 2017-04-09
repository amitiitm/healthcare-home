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

class UserType extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_types';

    use SoftDeletes;


    public function users(){
        return $this->hasMany('App\Models\User','user_type_id');
    }


}