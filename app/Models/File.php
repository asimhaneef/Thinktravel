<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['file_name', 'file_extension', 'file_real_path', 'file_size', 'file_mime_type'];

    public function fileable()
    {
        return $this->morphTo();
    }

}
