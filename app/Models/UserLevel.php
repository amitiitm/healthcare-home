<?php
/**
 * Created by PhpStorm.
 * User: anand
 * Date: 4/10/16
 * Time: 7:13 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    public function users()
    {
        return $this->hasMany('App\Models\User','level');
    }
}