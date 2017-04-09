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

class AgencyType extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'agency_types';

    use SoftDeletes;


    protected $hidden = ['created_at', 'updated_at','deleted_at'];
}