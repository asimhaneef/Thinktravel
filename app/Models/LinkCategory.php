<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkCategory extends Model
{
    /** @use HasFactory<\Database\Factories\LinkCategoryFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'is_active',
    ];
}
