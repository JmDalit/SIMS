<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SchoolCampusCurriculumRequest;
use App\Models\SchoolCampusCourseCurriculums;
use App\Models\SchoolCampusCourseCurriculumSubjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolCampusCurriculumController extends Controller
{


    public function index() {}

    public function store(SchoolCampusCurriculumRequest $request)
    {
        $data = $request->validated();

        foreach ($data['multi'] as $curKey => $curriculum) {
            $curriculumGet = SchoolCampusCourseCurriculums::updateOrCreate(
                ['id' => $curriculum['id']],
                [
                    'campus_course_id' => $curriculum['campus_course_id'],
                    'years'            => $curriculum['yearLevel'],
                    'created_by'       => Auth::user()->profile->fullname,
                ]
            );

            foreach ($data['multi'][$curKey]['subjects'] as $subKey => $subject) {
                SchoolCampusCourseCurriculumSubjects::updateOrCreate(
                    ['id' => $subject['id']],
                    [
                        'curriculum_id' => $curriculumGet->id,
                        'semester_id'   => $subject['semester_array']['id'],
                        'year'          => $subject['year'],
                        'name'          => $subject['name'],
                        'subject_code'  => $subject['subjectCode'],
                        'subject_class' => $subject['class_array']['name'],
                        'unit'          => $subject['unit'],
                        'created_by'    => Auth::user()->profile->fullname
                    ]
                );
            }
        }
    }
}
