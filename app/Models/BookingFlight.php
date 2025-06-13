<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingFlight extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFlightFactory> */
    use HasFactory;
    protected $fillable = [
        'flying_from',
        'flying_to', 
        'departing_date', 
        'returning_date',
        'userid', 
        'booking_id', 
        'total_days', 
        'flexibility', 
        'preferred_airline', 
        'is_active'
    ];
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function flightFrom()
    {
        return $this->belongsTo(Airport::class,"flying_from","id");
    }
    public function flightTo()
    {
        return $this->belongsTo(Airport::class,"flying_to","id");
    }
}
