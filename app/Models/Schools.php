<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schools extends Model
{
    protected $fillable = [
        'name',
        'shortcut',
        'reference_id',
        'created_by',
        'updated_by',
        'is_active',
        'is_delete',
        'photo',
    ];

    protected $appends = ['reference_array'];

    public function reference()
    {
        return $this->belongsTo(ListReferences::class, 'reference_id', 'id');
    }

    public function campuses()
    {
        return $this->hasMany(SchoolCampuses::class, 'school_id', 'id');
    }


    public function getReferenceArrayAttribute()
    {
        return $this->reference ?
            [
                'id' => $this->reference_id,
                'name' => $this->reference->name
            ] : null;
    }
}
