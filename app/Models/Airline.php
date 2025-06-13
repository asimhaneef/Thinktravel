<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    /** @use HasFactory<\Database\Factories\AirlineFactory> */
    use HasFactory;
    protected $fillable = [
        'airline_name',
        'iata',
        'icao',
        'callsign',
        'alies',
        'sort_order',
        'country_id',
        'airline_type_id'
    ];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function airline_type()
    {
        return $this->belongsTo(AirlineType::class);
    }
    public function bookingFlights()
    {
        return $this->hasMany(BookingFlight::class, 'preferred_airline');
    }
}
