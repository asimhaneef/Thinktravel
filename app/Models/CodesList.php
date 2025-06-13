<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodesList extends Model
{
    /** @use HasFactory<\Database\Factories\CodesListFactory> */
    use HasFactory;
    protected $fillable = [
        'codes_list',
        'data_entry',
        'label',
        'meaning',
        'is_active'
    ];
}
