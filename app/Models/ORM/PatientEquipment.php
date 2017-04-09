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

class PatientEquipment extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'patient_equipments';

    use SoftDeletes;

    public function ailment(){
        return $this->belongsTo('App\Models\ORM\Equipment','equipment_id');
    }


}