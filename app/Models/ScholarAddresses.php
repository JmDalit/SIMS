<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarAddresses extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'address',
        'barangay_code',
        'municipality_code',
        'province_code',
        'region_code'
    ];

    public function barangay()
    {
        return $this->belongsTo(LocationBarangays::class, 'barangay_code', 'code');
    }
    public function municipality()
    {
        return $this->belongsTo(LocationCity::class, 'municipality_code', 'code');
    }
    public function province()
    {
        return $this->belongsTo(LocationProvinces::class, 'province_code', 'code');
    }
    public function region()
    {
        return $this->belongsTo(LocationRegions::class, 'region_code', 'code');
    }
    public function scholar()
    {
        return $this->belongsTo(Scholars::class, 'scholar_id');
    }
}
