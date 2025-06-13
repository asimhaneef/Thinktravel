<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleForm extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFormFactory> */
    use HasFactory;
    protected $fillable = [
        'active',
        'entry_date',
        'inquiryid',
        'pnr',
        'sale_type',
        'air_usa',
        'air_other',
        'inquiry_no',
        'invoice_date',
        'location',
        'vacation',
        'insurance',
        'agent',
        'supplier',
        'fee_only',
        'balance_due',
        'last_name',
        'first_name',
        'no_of_pax',
        'ticket_no',
        'gross_supplier',
        'commission',
        'markup',
        'remarks_booking',
        'customer_mc',
        'customer_vi',
        'debit',
        'cash',
        'cheque_draft',
        'etransfer',
        'past_credit',
        'gift_card',
        'amex_payment',
        'other_payment',
        'remarks_payment',
        'departure_date',
        'return_date',
        'web_phone',
        'gds',
        'customer_card',
        'company_emax',
        'company_visa',
        'company_master',
        'other_supplier',
        'remarks_supplier',
        'other_remarks',
        'secondary_agent',
        'secondary_agent_share',
        'gst',
        'airline',
        'userid',
    ];
    
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent');
    }
    public function secondaryAgent()
    {
        return $this->belongsTo(User::class, 'secondary_agent');
    }
    public function customerDetails()
    {
        return $this->hasOne(Booking::class, 'inquiry_code', 'inquiry_no');
    }
    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier');
    }
    public function enquiry()
    {
        return $this->belongsTo(Booking::class, 'inquiryid');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
    public function enquiryDetails()
    {
        return $this->hasOne(Booking::class, 'inquiry_code', 'inquiry_no');
    }
}
