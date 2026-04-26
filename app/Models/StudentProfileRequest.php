<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfileRequest extends Model
{
    protected $connection = 'scholars';
    protected $table = 'profile_requests';
    public $timestamps = false;

    protected $fillable = [
        'civil_status',
        'email',
        
    ];


}
