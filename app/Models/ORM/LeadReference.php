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

class LeadReference extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
        protected $table = 'lead_references';

    use SoftDeletes;

    public function childrens(){
        return $this->hasMany('App\Models\ORM\LeadReference','parent_id');
    }
}