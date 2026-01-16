<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolCampusCourseCurriculumSubjects extends Model
{
    protected $fillable = [
        'curriculum_id',
        'semester_id',
        'year',
        'name',
        'subject_code',
        'subject_class',
        'unit',
        'created_by',
        'updated_by',
        'is_delete'
    ];

    protected $hidden = [
        'semester',
        'class',
        'curriculum_id'
    ];

    protected $appends = ['semester_array', 'class_array', 'is_lock'];

    protected function semester()
    {
        return $this->belongsTo(ListReferences::class, 'semester_id');
    }
    protected function curriculum()
    {
        return $this->belongsTo(SchoolCampusCourseCurriculums::class, 'curriculum_id');
    }

    protected function class()
    {
        return $this->belongsTo(ListReferences::class, 'subject_class', 'name');
    }

    protected function getSemesterArrayAttribute()
    {
        return $this->semester ? [
            'id'    => $this->semester->id,
            'name'  => $this->semester->name
        ] : null;
    }

    protected function getClassArrayAttribute()
    {
        return $this->class ? [
            'id'    => $this->class->id,
            'name'  => $this->class->name
        ] : null;
    }

    protected function getIsLockAttribute()
    {
        return true;
    }
}
