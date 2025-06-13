<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysParam extends Model
{
    use HasFactory;
    protected $fillable = ['param_name', 'param_value','is_active','recdate','description'];
}
