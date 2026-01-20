<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SchoolRequest;
use App\Models\SchoolCampusCourseCurriculums;
use App\Models\SchoolCampuses;
use App\Models\Schools;
use App\References\ListClass;
use App\References\LocationClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SchoolController extends Controller
{
    public function index(ListClass $ref, Request $request, LocationClass $location)
    {

        $schoolDetail = null;

        if ($id = request('id')) {
            $school = SchoolCampuses::with([
                'courses.subjects' => fn($q) => $q->where('is_delete', false),
                'grades' => fn($q) => $q->where('is_delete', false)->orderBy('grade', 'asc'),
                'info' => fn($q) => $q->select(['id', 'campus_id', 'dean', 'registrar', 'contact', 'email'])->where('is_delete', false),
                'semesters' => fn($q) => $q->where('is_delete', false),
            ])->find($id);

            // Convert entire object recursively to plain arrays
            $schoolDetail = $school ? json_decode(json_encode($school), true) : null;
        }

        return Inertia::render('Web/schoolPage', [
            //table shool
            'universities' => $ref->getSchools('table', $request->input('search')),
            //option ref
            'classOption' => $ref->getRefs('option', null, null, 'Class'),
            'classificationOption' => $ref->getRefs('option', null, null, 'Term Type'),
            'agencyOption' => $ref->getAgencies(false),
            'gradingOption' => $ref->getRefs('option', null, null, 'Grading System'),
            'courseOption' => $ref->getCourses('option'),
            'subClassOption' => $ref->getRefs('option', null, 'Subject', null),

            'semesterOption' => request('semesterType')
                ? $ref->getRefs('option', null, null, request('semesterType'))
                : null,
            //search
            'resultSearch' => request('autosuggest')
                ? ($location->getFullAddress(request('autosuggest'))?->toArray() ?? [])
                : [],
            'schoolDetail' => $schoolDetail,

            'subjectDetail' => request('campusCourseId')
                ? SchoolCampusCourseCurriculums::with([
                    'subjects' => fn($q) =>
                    $q->select(
                        'id',
                        'curriculum_id',
                        'semester_id',
                        'curriculum_id as curriculumId',
                        'name',
                        'subject_code as subjectCode',
                        'year',
                        'unit',
                        'subject_class',
                        'updated_at',
                        'updated_by',
                        'created_by',
                    )->where('is_delete', false)
                ])
                ->select([
                    'id',
                    'campus_course_id',
                    'total_year as yearNumber',
                    'years as yearLevel',
                    'semester_type_id as semesterTypeId'
                ])
                ->where('campus_course_id', request('campusCourseId'))
                ->where('semester_type_id', request('semTypeId'))
                ->where('is_delete', false)
                ->orderBy('id', 'desc')
                ->get()
                ->toArray()
                : null,


            'schoolEdit' => request('school_id')
                ? Schools::with(['campuses'])->find(request('school_id'))?->toArray()
                : null,
        ]);
    }

    public function store(SchoolRequest $request)
    {
        $data = $request->validated();

        $school = Schools::create([
            'name' => $data['name'],
            'shortcut' => $data['abbreviation'],
            'reference_id' => $data['class']['id'],
            'created_by'    => Auth::user()->profile->fullname
        ]);

        foreach ($data['campuses'] as $campus) {
            $slice = explode('-', $campus['address']['id']);
            $campusModel = $school->campuses()->create([
                'is_main' => $campus['main'],
                'term_id' => $campus['semester']['id'],
                'name' => $campus['name'],
                'agency_id' => $campus['agency']['id'],
                'grading_id' => $campus['grading']['id'],
                'start_date' => $campus['startDate'],
                'end_date' => $campus['endDate'],
                'created_by'    => Auth::user()->profile->fullname
            ]);

            $campusModel->address()->create([

                'address' => $campus['street'],
                'barangay_code' => $slice[0],
                'municipality_code' => $slice[1],
                'province_code' => $slice[2],
                'region_code' => $slice[3],
                'created_by'    => Auth::user()->profile->fullname
            ]);
        }

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'University Created',
            'message' => 'University successfully created.',
        ]);;
    }
    function update(SchoolRequest $request, string $id, string $type)
    {


        $data = $request->validated();
        $find = Schools::findOrFail($id);

        if ($type == 'form') {

            $find->update([
                'name' => $data['name'],
                'shortcut' => $data['abbreviation'],
                'reference_id' => $data['class']['id'],
                'updated_by'    => Auth::user()->profile->fullname,
                'updated_at'    => now()
            ]);


            foreach ($data['campuses'] as $campus) {
                $slice = explode('-', $campus['address']['id']);


                if (!empty($campus['id'])) {
                    $campusModel = $find->campuses()->find($campus['id']);
                    $campusModel->update([
                        'is_main'     => $campus['main'],
                        'term_id'     => $campus['semester']['id'],
                        'agency_id'   => $campus['agency']['id'],
                        'name'        => $campus['name'],
                        'start_date'  => $campus['startDate'],
                        'end_date'    => $campus['endDate'],
                        'grading_id'  => $campus['grading']['id'],
                        'updated_by'  => Auth::user()->profile->fullname,
                        'updated_at'  => now()
                    ]);
                    $campusModel->address()->update([
                        'address'     => $campus['street'],
                        'barangay_code' => $slice[0],
                        'municipality_code' => $slice[1],
                        'province_code' => $slice[2],
                        'region_code' => $slice[3],

                        'updated_at'    => now()
                    ]);
                } else {
                    $newCampus = $find->campuses()->create([
                        'is_main'     => $campus['main'],
                        'term_id'     => $campus['semester']['id'],
                        'agency_id'   => $campus['agency']['id'],
                        'grading_id'  => $campus['grading']['id'],
                        'created_by'  => Auth::user()->profile->fullname,
                    ]);

                    $newCampus->address()->create([
                        'address' => $campus['street'],
                        'barangay_code' => $slice[0],
                        'municipality_code' => $slice[1],
                        'province_code' => $slice[2],
                        'region_code' => $slice[3],
                        'created_by'    => Auth::user()->profile->fullname
                    ]);
                }
            }
        } else {
            $find->update([
                'is_active' => $data['isActive'],
            ]);
        }

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'University Updated',
            'message' => 'University successfully updated.',
        ]);
    }

    // function destroy(int $id)
    // {
    //     $find = LocationCity::findOrFail($id);
    //     $find->update([
    //         'is_delete' => true,
    //     ]);

    //     return redirect()->back()->with('flash', [
    //         'status' => 'success',
    //         'title'  => 'University Deleted',
    //         'message' => 'University successfully deleted.',
    //     ]);
    // }
}
