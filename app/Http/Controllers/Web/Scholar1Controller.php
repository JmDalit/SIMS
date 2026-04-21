<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Scholars;
use App\Models\ScholarSchoolInfos;
use App\Models\StudentSubjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Vinkla\Hashids\Facades\Hashids;

class Scholar1Controller extends Controller
{
    public function index(Request $request)
    {
        $schoolFilter = Inertia::optional(
            fn() => Scholars::with([
                'schoolInfo' => fn($q) => $q
                    ->select('id', 'scholar_id', 'campus_id')
                    ->with('campus:id,generated_name')
                    ->latest()
                    ->limit(1)
            ])
                ->get()
                ->map(function ($q) {
                    $school = $q->schoolInfo->first()?->campus;

                    return [
                        'id' => $school?->id,
                        'name' => $school?->generated_name,
                    ];
                })
                ->filter()
                ->unique('id')
                ->values()
        );
        $programFilter = Inertia::optional(
            fn() => Scholars::with([
                'program:id,name'
            ])
                ->get()
                ->map(function ($q) {


                    return [
                        'id' => $q->program->id,
                        'name' => $q->program->name
                    ];
                })
                ->filter()
                ->unique('id')
                ->values()
        );
        $subFilter = Inertia::optional(
            fn() => Scholars::with([
                'type:id,name'
            ])
                ->get()
                ->map(function ($q) {
                    return [
                        'id' => $q->type->id,
                        'name' => $q->type->name
                    ];
                })
                ->filter()
                ->unique('id')
                ->values()
        );

        $statusFilter = Inertia::optional(
            fn() => Scholars::with([
                'status:id,name'
            ])
                ->get()
                ->map(function ($q) {
                    return [
                        'id' => $q->status->id,
                        'name' => $q->status->name
                    ];
                })
                ->filter()
                ->unique('id')
                ->values()
        );

        $termRecordIds = StudentSubjectRequest::where('status', 'pending')->pluck('term_record_id')->toArray();

        return Inertia::render(
            'Web/scholarsPage',
            [
                'request_cnt' => Str::of(
                    StudentSubjectRequest::where('status', 'pending')->count()
                )->toString(),

                'scholars' =>
                Scholars::select(
                    'scholars.id',
                    'scholars.spas_no',
                    'scholars.status_id',
                    'scholars.program_id',
                    'scholars.type_id',
                    'scholars.award_year'
                )
                    ->join('scholar_profiles', 'scholar_profiles.scholar_id', '=', 'scholars.id')
                    ->with([
                        'status:id,name,icon,color_id',
                        'status.color:id,background_color,text_color',
                        'program:id,name',
                        'type:id,name',
                        'profile:id,scholar_id,photo,sex,fname,lname,mname,suffix,email,contact_no',
                        'schoolInfo' => fn($q) => $q
                            ->select('id', 'scholar_id', 'campus_id', 'campus_course_id')
                            ->with([
                                'campus:id,generated_name,agency_id',
                                'campus.agency:id,name,slug',
                                'campus.address:campus_id,region_code',
                                'course' => fn($q) => $q
                                    ->select('id', 'course_id')
                                    ->with([
                                        'course:id,name'
                                    ])
                            ])
                            ->latest()
                            ->limit(1),
                        'termRecords' => fn($q) => $q
                            ->select('id', 'scholar_id', 'term_id', 'level_id', 'academic_year', 'scholar_school_id', 'term_type_id')
                            ->with([
                                'requests' => fn($q) => $q
                                    ->select('id', 'term_record_id', 'subject_id', 'reviewed_at', 'reviewed_by', 'status', 'remarks')
                                    ->with([
                                        'subject:id,name,year,subject_code,unit,subject_class,semester_id'
                                    ])
                                    ->where('status', 'pending'),

                            ]),
                    ])
                    ->when($request->input('search'), fn($q) => $q->whereHas(
                        'profile',
                        fn($q) =>
                        $q->whereRaw("CONCAT(lname, ' ', fname, ' ', COALESCE(mname, '')) ILIKE ?", ['%' . $request->input('search') . '%'])
                    ))
                    ->when($request->input('schools'), function ($q, $schools) {
                        $q->whereHas('schoolInfo', fn($w) => $w->whereHas('campus', fn($r) => $r->whereIn('generated_name', $schools)));
                    })
                    ->when($request->input('programs'), function ($q, $programs) {
                        $q->whereHas('program', fn($w) => $w->whereIn('name', $programs));
                    })
                    ->when($request->input('sub'), function ($q, $sub) {
                        $q->whereHas('type', fn($w) => $w->whereIn('name', $sub));
                    })
                    ->when($request->input('status'), function ($q, $status) {
                        $q->whereHas('status', fn($w) => $w->whereIn('name', $status));
                    })

                    ->when($request->input('subjectRequest'), function ($q) use ($termRecordIds) {
                        $q->whereHas('termRecords', function ($w) use ($termRecordIds) {
                            $w->whereIn('id', $termRecordIds);
                        });
                    })
                    ->orderBy('scholar_profiles.lname', 'ASC')
                    ->paginate(10)
                    ->through(fn($q) => [
                        'id' => Hashids::encode($q->id),
                        'spas_no' => $q->spas_no,
                        'photo' => $q->profile?->photo,
                        'email' => $q->profile?->email,
                        'contact_no' => $q->profile?->contact_no,
                        'sex' => $q->profile?->sex,
                        'fullname' => trim(collect([
                            $q->profile?->lname . ',',
                            $q->profile?->fname,
                            $q->profile?->mname,
                            $q->profile?->suffix,
                        ])->filter()->implode(' ')),
                        'type' => $q->type?->name,
                        'program' => $q->program?->name,
                        'status' => [
                            'name' => $q->status?->name,
                            'bcolor' => $q->status?->color?->background_color,
                            'tcolor' => $q->status?->color?->text_color,
                            'icon' => $q->status?->icon
                        ],
                        'course' => $q->schoolInfo->first()?->course?->course?->name,
                        'school' => $q->schoolInfo->first()?->campus?->generated_name,
                        'awardyear' => $q->award_year,
                        'agency' => $q->schoolInfo->first()?->campus->agency?->slug,
                        'region' => $q->schoolInfo->first()?->campus->address?->region_array,
                        'request' => $q->termRecords
                            ->pluck('requests')
                            ->flatten()
                            ->isNotEmpty()
                    ]),
                'details' => Inertia::optional(function () use ($request) {
                    $id = Hashids::decode($request->input('id'))[0] ?? 0;
                    $q = Scholars::select(
                        'scholars.id',
                        'scholars.spas_no',
                        'scholars.status_id',
                        'scholars.program_id',
                        'scholars.type_id',
                        'scholars.award_year'
                    )
                        ->join('scholar_profiles', 'scholar_profiles.scholar_id', '=', 'scholars.id')
                        ->with([
                            'status:id,name,icon,color_id',
                            'status.color:id,background_color,text_color',
                            'program:id,name',
                            'type:id,name',
                            'profile:id,scholar_id,photo,sex,fname,lname,mname,suffix,email,contact_no',
                            'schoolInfo' => fn($q) => $q
                                ->select('id', 'scholar_id', 'campus_id', 'campus_course_id')
                                ->with([
                                    'campus:id,generated_name,agency_id',
                                    'campus.agency:id,name,slug',
                                    'campus.address:campus_id,region_code',
                                    'course' => fn($q) => $q
                                        ->select('id', 'course_id')
                                        ->with([
                                            'course:id,name'
                                        ])
                                ])
                                ->latest()
                                ->limit(1),
                            'termRecords' => fn($q) => $q
                                ->select('id', 'scholar_id', 'term_id', 'level_id', 'academic_year', 'scholar_school_id', 'term_type_id')
                                ->with([
                                    'requests' => fn($q) => $q
                                        ->select('id', 'term_record_id', 'subject_id', 'reviewed_at', 'reviewed_by', 'status', 'remarks')
                                        ->with([
                                            'subject:id,name,year,subject_code,unit,subject_class,semester_id'
                                        ])
                                        ->where('status', 'pending'),

                                ]),
                        ])
                        ->where('scholars.id', $id)
                        ->first();


                    return [
                        'spas_no' => $q?->spas_no,
                        'name' => $q?->name,
                        'type' => $q?->type?->name,
                        'program' => $q?->program?->name,
                        'email' => $q?->profile?->email,
                        'fullname' => trim(collect([
                            $q?->profile?->lname . ',',
                            $q?->profile?->fname,
                            $q?->profile?->mname,
                            $q?->profile?->suffix,
                        ])->filter()->implode(' ')),
                        'status' => [
                            'name' => $q?->status?->name,
                            'bcolor' => $q?->status?->color?->background_color,
                            'tcolor' => $q?->status?->color?->text_color,
                            'icon' => $q?->status?->icon
                        ],
                        'awardYear' => $q?->award_year,
                        'course' => $q?->schoolInfo->first()?->course->course->name,
                        'school' => $q?->schoolInfo->first()?->campus->generated_name,
                        'region' => $q?->schoolInfo->first()?->campus->address?->region_array,
                        'tr_request' => $q->termRecords
                            ->pluck('requests')
                            ->flatten()
                            ->count(),
                    ];
                }),
                'schoolOptions' =>  $schoolFilter,
                'programOptions' => $programFilter,
                'SubProgramOptions' => $subFilter,
                'statusOptions' => $statusFilter,
                'filterSearch' => $request->input('search') ?? null,
                'filterSchool' => $request->input('schools') != null ? Scholars::with([
                    'schoolInfo' => fn($q) => $q
                        ->select('id', 'scholar_id', 'campus_id')
                        ->with('campus:id,generated_name')
                        ->latest()
                        ->limit(1)
                ])
                    ->when($request->input('schools'), function ($q, $schools) {
                        $q->whereHas('schoolInfo', fn($w) => $w->whereHas('campus', fn($r) => $r->whereIn('generated_name', $schools)));
                    })
                    ->get()
                    ->map(function ($q) {
                        $school = $q->schoolInfo->first()?->campus;

                        return [
                            'id' => $school?->id,
                            'name' => $school?->generated_name,
                        ];
                    })
                    ->filter()
                    ->unique('id')
                    ->values()
                    : null
            ]
        );
    }
}
