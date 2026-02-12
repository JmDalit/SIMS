<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarParentDetails extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'fname',
        'id_no',
        'id_date',
        'id_place',
        'companion'
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholars::class, 'scholar_id');
    }
}
