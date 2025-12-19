<?php

namespace App\References;

use App\Models\SchoolCampuses;
use Illuminate\Support\Facades\DB;

class ScholarClass
{
    public function getScholar()
    {
        //for presentation only

        return DB::connection('scholars')
            ->table('users as a')
            ->leftJoin('s_details as b', 'b.spas_no', '=', 'a.spas_no')
            ->leftJoin('school_campuses as c', 'c.id', '=', 'b.campus_id')
            ->leftJoin('schools as d', 'd.id', '=', 'c.school_id')
            ->leftJoin('list_courses as e', 'e.id', '=', 'b.campus_id')
            ->where('a.is_active', true)
            ->select(
                'a.*',
                'b.program',
                'b.sex',
                'b.contact',
                'b.civil_status',
                'd.name as school',
                'd.shortcut',
                'e.name as course'
            )
            ->paginate(10);
    }

    public function getGrades($id)
    {
        return DB::connection('scholars')
            ->table('student_subjects as ss')
            ->join('school_campus_course_subjects as sccs', 'ss.subject_id', '=', 'sccs.id')
            ->leftJoin('student_grades as sg', 'ss.id', '=', 'sg.student_subject_id')
            ->leftJoin('list_statuses as ls', 'sg.status_id', '=', 'ls.id')
            ->where('ss.spas_no', $id)
            ->orderBy('sccs.subject_code')
            ->select(
                'ss.term',
                'ss.year_level',
                'ss.school_year',
                'sccs.subject_code',
                'sccs.name',
                'sccs.unit',
                'sg.grade',
                'ls.name as status'
            )
            ->get()
            ->groupBy(
                fn($row) =>
                $row->term . '|' . $row->year_level . '|' . $row->school_year
            )
            ->map(function ($rows) {

                $first = $rows->first();

                return [
                    'term' => $first->term,
                    'year_level' => $first->year_level,
                    'school_year' => $first->school_year,
                    'subjects' => $rows->map(function ($row) {
                        return [
                            'code' => $row->subject_code,
                            'subject_name' => $row->name,
                            'unit' => $row->unit,
                            'grade' => $row->grade,
                            'status' => $row->status,
                        ];
                    })->values()
                ];
            })
            ->values();
    }
}
