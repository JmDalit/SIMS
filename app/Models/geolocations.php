<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class geolocations extends Model
{
    protected $fillable = [
        'psgc_code',
        'name',
        'old_name',
        'district',
        'zipcode',
        'type',
    ];
}
