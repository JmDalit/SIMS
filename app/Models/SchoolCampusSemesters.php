<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class SchoolCampusSemesters extends Model
{
    protected $fillable = [
        'campus_id',
        'semester_id',
        'start_date',
        'end_date',
        'is_delete',
        'is_active',
        'submission_date'
    ];


    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'submission_date' => 'date:Y-m-d',
    ];

    protected $hidden = [
        'is_delete',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['semester_array'];

    public function campus()
    {
        return $this->belongsTo(SchoolCampuses::class, 'campus_id');
    }

    public function semester()
    {
        return $this->belongsTo(ListReferences::class, 'semester_id');
    }

    public function getSemesterArrayAttribute()
    {
        return $this->semester ? $this->semester->only(['id', 'name']) : null;
    }

    // public function setStartDateAttribute($value)
    // {
    //     $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d');
    // }

    // public function setEndDateAttribute($value)
    // {
    //     $this->attributes['end_date'] = Carbon::parse($value)->format('Y-m-d');
    // }
}
