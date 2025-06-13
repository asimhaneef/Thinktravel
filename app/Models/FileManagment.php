<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileManagment extends Model
{
    /** @use HasFactory<\Database\Factories\FileManagmentFactory> */
    use HasFactory;
    protected $fillable = [
        'userid',
        'active',
        'document_description',
        'document_name',
        'document_access',
        'document_category',
    ];
    public function files()
    {
        return $this->morphOne(File::class, 'fileable')->orderBy('id', 'desc');
    }
}
