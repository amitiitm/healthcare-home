<?php
namespace App\Models\ORM;
use Illuminate\Database\Eloquent\Model;



/**
 * Created by YBT.
 * User: Vineet Kumar
 */

class Order extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';
    
    public function invoices()
    {
        return $this->hasMany('App\Models\ORM\Invoice');
    }

}