<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolCampusInfo extends Model
{
    protected $fillable = [
        'campus_id',
        'dean',
        'registrar',
        'contact',
        'email',
        'created_by',
        'updated_by',
        'is_delete',
        'is_active',
    ];


    public function campus()
    {
        return $this->belongsTo(SchoolCampuses::class, 'campus_id');
    }
}
