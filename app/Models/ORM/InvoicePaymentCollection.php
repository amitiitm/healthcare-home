<?php

namespace App\Models\ORM;

use Illuminate\Database\Eloquent\Model;
/**
 * Created by YBT.
 * User: Amit Pandey
 */
class InvoicePaymentCollection extends Model
{
    protected $table = 'invoice_payment_collections';
    protected $fillable = [
        'invoice_id','payment_mode_id','payment_status','paid_amt','outstanding_amt','bank_name',
        'cheque_no','outstanding_amt_type','recoverable_outstanding_amt_status','nonrecoverable_outstanding_amt_status',
        'expected_payment_date'
    ];
    public function invoice()
    {
        return $this->belongsTo('App\Models\ORM\Invoice');
    }
}
