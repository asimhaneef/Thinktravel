<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAgentComments extends Model
{
    /** @use HasFactory<\Database\Factories\BookingAgentCommentsFactory> */
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'comment',
        'agent_id',
        'active'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
