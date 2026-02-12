<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarProfiles extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'fname',
        'lname',
        'mname',
        'suffix',
        'photo',
        'contact_no',
        'birthdate',
        'birthplace',
        'email',
        'sex',
        'religion',
        'civil_status'
    ];


    public function scholar()
    {
        return $this->belongsTo(Scholars::class, 'scholar_id');
    }
}
