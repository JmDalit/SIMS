<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumReplication extends Model
{
    protected $fillable = [
        'curriculum_id',
        'user_id',
    ];

    public function curriculum()
    {
        return $this->belongsTo(SchoolCampusCourseCurriculums::class, 'curriculum_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
