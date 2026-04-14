<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSubjectRequest extends Model
{
    protected $connection = 'scholars';
    protected $table = 'student_subject_requests';
    public $timestamps = false;
    protected $fillable = [
        'term_record_id',
        'subject_id',
        'reviewed_at',
        'reviewed_by',
        'status',
        'remarks',
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
