<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGradeRequest extends Model
{
    public $timestamps = false;
    protected $connection = 'scholars';
    protected $table = 'student_grade_requests';

    protected $fillable = [
        'term_record_id',
        'subject_id',
        'reviewed_at',
        'reviewed_by',
        'status',
        'remarks',
    ];
}
