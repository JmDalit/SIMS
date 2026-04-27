<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGradeRequest extends Model
{
    public $timestamps = false;
    protected $connection = 'scholars';
    protected $table = 'student_grade_requests';

    protected $fillable = [
        'term_grades_id',
        'term_record_id',
        'grades_id',
        'reviewed_at',
        'reviewed_by',
        'status',
        'remarks',
    ];


    public function scholarGrade()
    {
        return $this->belongsTo(ScholarSchoolGrades::class, 'term_grades_id');
    }

    public function grade()
    {
        return $this->belongsTo(SchoolCampusGrades::class, 'grades_id');
    }


    public function termRecord()
    {
        return $this->belongsTo(ScholarTerm::class, 'term_record_id');
    }
}
