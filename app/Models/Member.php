<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name', 
        'last_name', 
        'phone_no',
        'gender',        
        'postal_code',
        'email',
        'address',
        'city',
        'country_id',
        'member_code',
        'user_name', 
        'password'
    ];
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
