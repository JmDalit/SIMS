<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Scholars;
use App\Models\ScholarSchoolInfos;
use App\Models\StudentSubjectRequest;
use App\References\LocationClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Vinkla\Hashids\Facades\Hashids;

class Scholar1Controller extends Controller
{

    public function index(Request $request, LocationClass $location)
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
                'details' => Inertia::optional(function () use ($request, $location) {
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
                            'address:id,scholar_id,region_code,province_code,municipality_code,barangay_code,address',
                            'program:id,name',
                            'type:id,name',
                            'profile:id,scholar_id,photo,sex,fname,lname,mname,suffix,email,contact_no,birthplace,birthdate,religion,civil_status',
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
                        'type' => $q?->type?->name,
                        'program' => $q?->program?->name,
                        'email' => $q?->profile?->email,
                        'contact_no' => $q?->profile?->contact_no,
                        'fname' => $q?->profile?->fname,
                        'mname' => $q?->profile?->mname,
                        'lname' => $q?->profile?->lname,
                        'suffix' => $q?->profile?->suffix,
                        'birthplace' => $q?->profile?->birthplace,
                        'birthdate' => Carbon::parse($q?->profile?->birthdate)->format('Y-m-d'),
                        'religion' => $q?->profile?->religion,
                        'civil_status' => $q?->profile?->civil_status,
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
                        'address'   => [
                            'address'      => $q?->address?->address,
                            'province'     => $q?->address?->province_array,
                            'region'       => $q?->address?->region_array,
                            'municipality' => $q?->address?->municipality_array,
                            'barangay'     => $q?->address?->barangay_array,
                        ],
                        'fullAddress' => $q?->address?->full_address,
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
                'resultSearch' => request('findAddress')
                    ? ($location->getFullAddress(request('findAddress')) ?? [])
                    : [],
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


    public function update(int $id, string $type, Request $request)
    {
        try {
            $scholar = Scholars::findOrFail($id);

            if ($type === 'personal_info') {
                $data = $request->validate([
                    'fname' => 'required|string|max:255',
                    'mname' => 'nullable|string|max:255',
                    'lname' => 'required|string|max:255',
                    'suffix' => 'nullable|string|max:255',
                    'email' => 'required|email|max:255',
                    'contact_no' => 'nullable|string|max:20',
                    'birthplace' => 'nullable|string|max:255',
                    'birthdate' => 'nullable|date',
                    'religion' => 'nullable|string|max:255',
                    'civil_status' => 'nullable|string|max:255',
                ]);

                $scholar->profile()->updateOrCreate(
                    ['scholar_id' => $scholar->id],
                    [
                        'fname' => $data['fname'],
                        'mname' => $data['mname'],
                        'lname' => $data['lname'],
                        'suffix' => $data['suffix'],
                        'email' => $data['email'],
                        'contact_no' => $data['contact_no'],
                        'birthplace' => $data['birthplace'],
                        'birthdate' => $data['birthdate'],
                        'religion' => $data['religion'],
                        'civil_status' => $data['civil_status'],
                    ]
                );

                $scholar->address()->updateOrCreate(
                    ['scholar_id' => $scholar->id],
                    [
                        'address' => $request->input('address'),
                        'region_code' => $request->input('region_code'),
                        'province_code' => $request->input('province_code'),
                        'municipality_code' => $request->input('municipality_code'),
                        'barangay_code' => $request->input('barangay_code'),
                    ]
                );
            }
            return back()->with([
                'message' => 'Information updated successfully.',
                'type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                'message' => 'An error occurred.' . $th->getMessage(),
                'type' => 'error'
            ]);
        }
    }
}
