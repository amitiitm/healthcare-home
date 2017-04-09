<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CgTraining extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cg_training';

    use SoftDeletes;

    
}