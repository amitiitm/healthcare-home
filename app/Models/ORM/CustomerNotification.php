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

class CustomerNotification extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customer_notifications';

    use SoftDeletes;


    protected $hidden = ['created_at', 'updated_at','deleted_at'];


    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }
}