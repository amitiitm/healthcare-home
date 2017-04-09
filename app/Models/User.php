<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','user_type_id','phone'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    // User has many articles
  	public function deviceTokens()
  	{
  		return $this->hasMany('App\Models\ORM\DeviceToken','user_id');
  	}
	public function contributedArticles()
	{
		return $this->belongsToMany('App\Models\Article','article_contributor', 'article_id', 'contributor_id')->withTimestamps();
	}
    public function level()
    {
        return $this->belongsTo('App\Models\UserLevel','level');
    }

    public function employeeInfo(){
        return $this->hasOne('App\Models\ORM\UserEmployee','user_id');
    }

    public function employeeTracking(){
        return $this->hasMany('App\Models\ORM\VendorTracker','vendor_user_id');
    }

    use SoftDeletes;
}
