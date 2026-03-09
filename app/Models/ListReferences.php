<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListReferences extends Model
{


    protected $fillable = [
        'name',
        'classification',
        'type',
        'color',
        'others',
        'is_active',
        'is_delete',
        'created_by',
        'updated_by'
    ];

    protected $appends = ['classification_array', 'type_array', 'color_array', 'reference_array'];

    public function school()
    {
        return $this->hasMany(Schools::class, 'reference_id', 'id');
    }

    public function campus()
    {
        return $this->hasMany(SchoolCampuses::class, 'term_id');
    }

    public function level()
    {
        return $this->hasMany(ScholarTerm::class, 'level_id');
    }

    public function getClassificationArrayAttribute()
    {
        return $this->classification ? [
            'id' => $this->classification,
            'name' => $this->classification,
        ] : null;
    }

    public function getTypeArrayAttribute()
    {
        return $this->type ? [
            'id' => $this->type,
            'name' => $this->type,
        ] : null;
    }
    public function getColorArrayAttribute()
    {
        return $this->color ? [
            'id' => $this->color,
            'name' => ucfirst($this->color),
        ] : null;
    }

    public function getReferenceArrayAttribute()
    {
        return $this->name ? [
            'id' => $this->id,
            'name' => $this->name,
        ] : null;
    }
}
