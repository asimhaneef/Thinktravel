<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission; // ✅ Use Spatie's Permission Model

class Permission extends SpatiePermission
{
    use HasFactory; // ✅ No need for HasRoles here

    protected $fillable = [
        'name',
        'guard_name'
    ];
}