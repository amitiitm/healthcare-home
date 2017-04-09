<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CgReplacement extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cg_replacement';

    use SoftDeletes;

    
}