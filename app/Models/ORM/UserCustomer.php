<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Created by PhpStorm.
 * User: SYMB
 * Date: 6/7/2016
 * Time: 4:46 PM
 */
class UserCustomer extends Model{
    protected $table = 'user_customers';

    public function UserVendor() {
        return $this->belongsTo('App\Models\UserCustomer');
    }
}