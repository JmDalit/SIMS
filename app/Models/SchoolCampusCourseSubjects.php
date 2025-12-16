<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class SchoolCampusCourseSubjects extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'subject_code',
        'subject_class',
        'unit',
        'created_by',
        'updated_by',
        'verified_by',
        'verified_at',
        'is_delete'
    ];

    protected $appends = ['formatted_date', 'class_array'];

    protected $casts = [
        'is_delete' => 'boolean',
    ];

    public function campusCourse()
    {
        return $this->belongsTo(SchoolCampusCourses::class, 'course_id', 'id');
    }

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('M d, Y | h:i a');
    }

    public function subjectClass()
    {
        return $this->belongsTo(ListReferences::class, 'subject_class', 'name');
    }

    public function getClassArrayAttribute()
    {
        return $this->subjectClass ?
            [
                'id'    => $this->subjectClass->id,
                'name'  => $this->subject_class
            ] : null;
    }
}
