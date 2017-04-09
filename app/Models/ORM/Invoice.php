<?php

namespace App\Models\ORM;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by YBT.
 * User: Vineet Kumar
 */
class Invoice extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoices';
    protected $fillable = [
        'order_id',
        'lead_id',
        'price_per_day',
        'total_amount',
        'remaining_amount',
        'discount_amount',
        'tax_amount',
        'payment_type_id',
        'payment_period_id',
        'payment_mode_id',
        'customer_name',
        'invoice_type',
        'email',
        'address',
        'landmark',
        'phone',
        'invoice_to_date','date_of_collection',
        'payumoney_url', 'invoice_number', 'assigned_user_id', 'assigned_user_comments'
    ];

    public function order() {
        return $this->belongsTo('App\Models\ORM\Order');
    }

    public function scopeinvoiceByOrderId($query, $order_id) {
        return $query->where('order_id', $order_id)->get();
    }

    public static function parseDropDownValues($dropdown_values) {
        $drop_down_array = [];
        foreach ($dropdown_values as $dropdown_value) {
            $drop_down_array[$dropdown_value->id] = $dropdown_value->label;
        }
        return $drop_down_array;
    }
    
    public static function parseUserDropDownValues($dropdown_values) {
        $drop_down_array = [];
        foreach ($dropdown_values as $dropdown_value) {
            $drop_down_array[$dropdown_value->id] = $dropdown_value->name;
        }
        return $drop_down_array;
    }

}
