<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarSchoolInfos extends Model
{
    protected $fillable = [
        'campus_id',
        'campus_course_id',
        'award_year',
        'graduated_year',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholars::class, 'scholar_id');
    }

    public function campus()
    {
        return $this->belongsTo(SchoolCampuses::class, 'campus_id');
    }

    public function campusCourse()
    {
        return $this->belongsTo(SchoolCampusCourses::class, 'campus_course_id');
    }
}
