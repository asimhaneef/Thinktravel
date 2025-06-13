<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;
    protected $fillable = [
        'inquiry_code',
        'booking_type',
        'flight_type',
        'cruising_origin',
        'cruising_location',
        'cruising_month',
        'add_hotel',
        'hotel_checkin_date', 
        'hotel_checkout_date',
        'travel_doc', 
        'travel_doc_nationality',
        'travel_doc_other',
        'additional_notes',
        'adults',
        'children',
        'infants',

        'vacation_origin',
        'vacation_destination',
        'vacation_total_days',
        'vacation_flexibility',
        'vacation_preferred_airline',
        'vacation_departing_date',
        
        'member_id',
        'usa_connection',
        'payment_mode',
        'agent_name', 
        'agree_on_terms', 
        'contact_by_email',
        'images_attachment',
        'booking_status', 
        'customer_identification', 
        'quote_submitted',
        'customer_contacted', 
        'booking_ref',
        'payment_received', 
        'insurance_purchased', 
        'ticket_issued', 
        'reservation_made', 
        'userid'
    ];
    
    public function bookingFlight()
    {
        return $this->hasMany(BookingFlight::class);
    }
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_name');
    }
    public function vacationOrigin()
    {
        return $this->belongsTo(Airport::class, 'vacation_origin');
    }
    public function vacationDestination()
    {
        return $this->belongsTo(Country::class, 'vacation_destination');
    }
    public function cruisesOrigin()
    {
        return $this->belongsTo(Airport::class, 'cruising_origin');
    }
    public function cruisesDestination()
    {
        return $this->belongsTo(Region::class, 'cruising_location');
    }
    public function agentComments()
    {
        return $this->hasMany(BookingAgentComments::class);
    }
    public function files()
    {
        return $this->morphOne(File::class, 'fileable')->orderBy('id', 'desc');
    }
}
