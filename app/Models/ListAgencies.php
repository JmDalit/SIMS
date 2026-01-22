<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListAgencies extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'address',
        'barangay_code',
        'city_code',
        'province_code',
        'region_code',
        'is_active',
        'is_delete'
    ];


    protected $hidden = [
        'is_active',
        'is_delete',
        'updated_by',
        'created_by',
        'created_at',
        'updated_at'
    ];



    public function profile()
    {
        return $this->hasMany(UserProfile::class, 'agency_id');
    }
}
