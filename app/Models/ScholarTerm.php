<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarTerm extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'scholar_term_records';
    protected $fillable = [
        'term_id',
        'scholar_id',
        'level_id',
        'academic_year',
        'scholar_school_id',
        'term_type_id'
    ];

    public function term()
    {
        return $this->belongsTo(ListReferences::class, 'term_id');
    }
    public function scholar()
    {
        return $this->belongsTo(Scholars::class, 'scholar_id');
    }

    public function termType()
    {
        return $this->belongsTo(ListReferences::class, 'term_type_id');
    }

    public function schoolInfo()
    {
        return $this->belongsTo(ScholarSchoolInfos::class, 'scholar_school_id');
    }

    public function level()
    {
        return $this->belongsTo(ListReferences::class, 'level_id');
    }

    public function subjects()
    {
        return $this->hasMany(ScholarSchoolGrades::class, 'term_record_id');
    }

    public function requests()
    {
        return $this->hasMany(StudentSubjectRequest::class, 'term_record_id');
    }

    public function gradeRequests()
    {
        return $this->hasMany(StudentGradeRequest::class, 'term_record_id');
    }
}
