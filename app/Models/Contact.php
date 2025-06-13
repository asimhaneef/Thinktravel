<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;

    protected $fillable = [
        'contact_email',
        'contact_phone',
        'continue',
        'first_name',
        'last_name',
        'formname',
        'message'
    ];
}
