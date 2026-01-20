<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SchoolCampusCurriculumRequest;
use App\Models\SchoolCampusCourseCurriculums;
use App\Models\SchoolCampusCourseCurriculumSubjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
                    'semester_type_id' => $curriculum['semesterTypeId']
                ]
            );

            foreach ($data['multi'][$curKey]['subjects'] as $subKey => $subject) {
                $model = SchoolCampusCourseCurriculumSubjects::updateOrCreate(
                    ['id' => $subject['id']],
                    [
                        'curriculum_id' => $curriculumGet->id,
                        'semester_id'   => $subject['semester_array']['id'],
                        'year'          => $subject['year'],
                        'name'          => Str::lower($subject['name']),
                        'subject_code'  => $subject['subjectCode'],
                        'subject_class' => $subject['class_array']['name'],
                        'unit'          => $subject['unit'],
                        'updated_by'    => Auth::user()->profile->fullname
                    ]
                );

                if ($model->wasRecentlyCreated) {
                    $model->created_by = Auth::user()->profile->fullname;
                    $model->save();
                }
            }
        }

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Curriculum Created',
            'message' => 'Curriculum successfully created.',
        ]);;
    }
    public function destroySubject(int $id)
    {
        $find = SchoolCampusCourseCurriculumSubjects::findOrFail($id);
        $find->update([
            'is_delete' => true,
        ]);

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Subject Deleted',
            'message' => 'Subject successfully deleted.',
        ]);
    }

    public function destroyCurriculum(int $id)
    {
        $find = SchoolCampusCourseCurriculums::findOrFail($id);
        $find->update([
            'is_delete' => true,
        ]);

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Curriculum Deleted',
            'message' => 'Curriculum successfully deleted.',
        ]);
    }
}
