<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholars extends Model
{

    protected $fillable = [
        'spas_no',
        'status_id',
        'program_id',
        'type_id',
        'category_id',
        'status_id',
        'is_active',
        'is_delete',
        'created_by',
        'updated_by',
        'verified_by',
        'verified_at',
    ];

    public function status()
    {
        return $this->belongsTo(ListStatuses::class, 'status_id');
    }

    public function program()
    {
        return $this->belongsTo(ListPrograms::class, 'program_id');
    }

    public function type()
    {
        return $this->belongsTo(ListReferences::class, 'type_id');
    }
}
