<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListColors extends Model
{
    protected $fillable = [
        'background_color',
        'text_color',
        'is_active',
        'is_delete',
        'created_by',
        'updated_by'
    ];


    public function statuses()
    {
        return $this->hasMany(ListStatuses::class, 'color_id', 'id');
    }
}
