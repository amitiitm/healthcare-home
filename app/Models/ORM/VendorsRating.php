<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VendorsRating extends Model{

	protected $guarded = [];
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vendors_rating';

    use SoftDeletes;

    
}