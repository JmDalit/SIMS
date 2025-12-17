<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ListStatuses extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'type',
        'color_id',
        'is_active',
        'is_delete',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['color_array'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_delete' => 'boolean',
    ];

    public function color()
    {
        return $this->belongsTo(ListColors::class, 'color_id', 'id');
    }

    public function getColorArrayAttribute()
    {
        return $this->color ? [
            'id' => $this->color_id,
            'name' => trim($this->color->background_color . ' ' . $this->color->text_color),
            'bgColor' => $this->color->background_color,
            'textColor' => $this->color->text_color
        ] : null;
    }
}
