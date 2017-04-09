<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ComplaintsCategories extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'complaints_categories';

    use SoftDeletes;

}