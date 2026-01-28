<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListPrograms extends Model
{
    protected $fillable = [
        'name',
        'others',
        'is_sub',
        'is_active',
        'program_id',
        'type_id',
        'created_by',
        'updated_by',
        'is_delete'
    ];

    protected $hidden = [
        'is_delete',
        'program',
        'type'
    ];

    protected $appends = [
        'program_array',
        'type_array',
    ];


    public function program()
    {
        return $this->belongsTo(ListReferences::class, 'program_id');
    }
    public function type()
    {
        return $this->belongsTo(ListReferences::class, 'type_id');
    }


    public function getProgramArrayAttribute()
    {
        return $this->program ?
            [
                'id' => $this->program->id,
                'name' => $this->program->name
            ]
            : null;
    }

    public function getTypeArrayAttribute()
    {
        return $this->type ?
            [
                'id' => $this->type->id,
                'name' => $this->type->name
            ] : null;
    }
}
