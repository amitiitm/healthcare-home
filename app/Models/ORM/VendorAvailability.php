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

class VendorAvailability extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vendor_availabilities';

    use SoftDeletes;


    public function availabilityOption(){
        return $this->belongsTo('App\Models\ORM\VendorAvailabilityOption','option_id');
    }
    public function availabilityReason(){
        return $this->belongsTo('App\Models\ORM\VendorAvailabilityOptionReason','reason_id');
    }
    public function availabilityShift(){
        return $this->belongsTo('App\Models\ORM\Shift','changed_shift_id');
    }
    public function availabilityZone(){
        return $this->belongsTo('App\Models\ORM\LocationZone','changed_zone_id');
    }

    protected $hidden = ['created_at', 'updated_at','deleted_at'];
}