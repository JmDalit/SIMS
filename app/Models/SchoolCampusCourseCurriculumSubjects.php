<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SchoolCampusCourseCurriculumSubjects extends Model
{
    protected $connection = 'pgsql';

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
        'is_delete'
    ];

    protected $appends = ['semester_array', 'class_array', 'is_lock', 'updated_at_formatted', 'created_at_formatted'];

    public function semester()
    {
        return $this->belongsTo(ListReferences::class, 'semester_id');
    }
    public function curriculum()
    {
        return $this->belongsTo(SchoolCampusCourseCurriculums::class, 'curriculum_id');
    }

    public function class()
    {
        return $this->belongsTo(ListReferences::class, 'subject_class', 'name');
    }

    public function getSemesterArrayAttribute()
    {
        return $this->semester ? [
            'id'    => $this->semester->id,
            'name'  => $this->semester->name
        ] : null;
    }

    public function getClassArrayAttribute()
    {
        return $this->class ? [
            'id'    => $this->class->id,
            'name'  => $this->class->name
        ] : null;
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return Carbon::parse($this->updated_at)
            ->format('M d, Y | g:ia'); // Jan 16, 2026 | 7:22am
    }

    // Similarly, for created_at if needed
    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->created_at)
            ->format('M d, Y | g:ia');
    }

    public function getIsLockAttribute()
    {
        return true;
    }
}
