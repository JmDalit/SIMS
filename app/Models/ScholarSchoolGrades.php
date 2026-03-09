<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarSchoolGrades extends Model
{
    protected $fillable = [
        'term_record_id',
        'subject_id',
        'remarks',
        'grades_id'
    ];


    public function termRecord()
    {
        return $this->belongsTo(ScholarTerm::class, 'term_record_id');
    }

    public function subject()
    {
        return $this->belongsTo(SchoolCampusCourseCurriculumSubjects::class, 'subject_id');
    }
}
