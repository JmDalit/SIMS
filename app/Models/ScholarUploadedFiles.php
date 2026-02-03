<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarUploadedFiles extends Model
{
    protected $fillable = [
        'filename',
        'filepath',
        'region_office',
        'created_by',
        'validated_by',
        'validated_at',

    ];
}
