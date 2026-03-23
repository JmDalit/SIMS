<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ScholarRequest;
use App\Imports\CheckScholarImport;
use App\Imports\ScholarImport;
use App\Models\Scholars;
use App\Models\ScholarSchoolInfos;
use App\Models\ScholarTerm;
use App\Models\ScholarUploadedFiles;
use App\Models\User;
use App\Notifications\ScholarUploadedNotification;
use App\Notifications\ValidatedFilesNotification;
use App\References\LocationClass;
use App\References\ScholarClass;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Vinkla\Hashids\Facades\Hashids;

use function Symfony\Component\Clock\now;

class ScholarController extends Controller
{
    public function index(LocationClass $location)
    {

        $scholars = Scholars::select('id', 'spas_no', 'status_id', 'program_id', 'type_id', 'award_year', 'verified_by', 'verified_at', 'validate_status');

        $scholarDetails = request('id') ?  Scholars::select('id', 'spas_no', 'status_id', 'program_id', 'type_id', 'award_year', 'verified_by', 'verified_at', 'validate_status')
            ->with([
                'status:id,name,icon,color_id',
                'status.color:id,background_color,text_color',
                'program:id,name',
                'type:id,name',
                'profile:scholar_id,photo,sex,fname,lname,mname,suffix,email,contact_no,birthplace,birthdate,civil_status,religion',
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
                    ->limit(1)
            ])
            ->where('id', Hashids::decode(request('id'))[0] ?? 0)
            ->first() : null;


        return Inertia::render('Web/scholarPage', [
            'scholar' => $scholars
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
                        ->limit(1)
                ])
                ->when(request('search'), fn($q) => $q->whereHas(
                    'profile',
                    fn($q) =>
                    $q->whereRaw("CONCAT(lname, ' ', fname, ' ', COALESCE(mname, '')) ILIKE ?", ['%' . request('search') . '%'])
                ))
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
                    'verified_by' => $q->verified_by,
                    'verified_at' => $q->verified_at,
                    'validate_status' => $q->validate_status,
                ]),
            'scholarDetails' =>
            [
                'spas_no' => $scholarDetails?->spas_no,
                'photo' => $scholarDetails?->profile?->photo,
                'sex' => $scholarDetails?->profile?->sex,
                'fname' => $scholarDetails?->profile?->fname,
                'mname' => $scholarDetails?->profile?->mname,
                'lname' => $scholarDetails?->profile?->lname,
                'suffix' => $scholarDetails?->profile?->suffix,
                'email' => $scholarDetails?->profile?->email,
                'contact_no' => $scholarDetails?->profile?->contact_no,
                'birthplace' => $scholarDetails?->profile?->birthplace,
                'birthdate' => Carbon::parse($scholarDetails?->profile?->birthdate)->format('Y-m-d'),
                'religion' => $scholarDetails?->profile?->religion,
                'civil_status' => $scholarDetails?->profile?->civil_status,
            ],
            'academic' => request('id') ?
                ScholarSchoolInfos::select(
                    'id',
                    'campus_id',
                    'curriculum_id',
                    'campus_course_id',
                    'school_year'
                )
                ->with([
                    'campus' => fn($q) =>
                    $q->select('id', 'generated_name', 'term_id')
                        ->with(['term:id,name']),

                    'course' => fn($q) =>
                    $q->select('id', 'course_id'),

                    'termRecords' => fn($q) =>
                    $q->select('id', 'scholar_school_id', 'term_id', 'level_id')
                        ->with([
                            'term:id,name',
                            'level:id,name',
                            'subjects'
                        ])
                ])
                ->where('scholar_id', Hashids::decode(request('id'))[0] ?? 0)
                ->get()
                ->map(function ($q) {

                    $records = $q->termRecords
                        ->groupBy(fn($r) => $r->level->name)
                        ->map(function ($terms, $levelName) {

                            return [
                                'label' => $levelName . ' Year',
                                'items' => $terms->map(function ($term, $termIndex) {
                                    return [
                                        'term_id' => Hashids::encode($term->id),
                                        'index' => $termIndex,
                                        'label' => $term->term->name,
                                        'grades' => $term->subjects
                                    ];
                                })->values()
                            ];
                        })->values();

                    return [
                        'name' => $q->campus->generated_name,
                        'course' => $q->course?->course?->name,
                        'sy' => $q->school_year,
                        'term' => $q->campus?->term_array,
                        'records' => $records
                    ];
                })
                : null,
            'files' => request('OpenFiles')
                ? ScholarUploadedFiles::when(
                    Auth::check() && Auth::user()->role_array['name'] == 'regional staff',
                    function ($query) {
                        $query->where('region_office', Auth::user()->profile->agency_array['name']);
                    }
                )->When(request('status'), function ($q) {
                    $q->where('status', Str::lower(request('status')));
                })
                ->latest()
                ->paginate(4)
                : null,
            'statuses' => Scholars::selectRaw('status_id, count(*) as total')
                ->with(['status:id,icon,color_id,name'])
                ->groupBy('status_id')
                ->get()
                ->map(fn($q) => [
                    'status'        => Str::ucwords($q->status->name),
                    'icon'          => $q->status->icon,
                    'color_array'   => $q->status->color_array,
                    'total'   => $q->total,
                ]),
            'georesult' => request('geosearch') ? ($location->getFullAddress(request('geosearch'), false) ?? [])
                : [],

        ]);
    }

    // public function details(string $spas_no) {}

    public function store(ScholarRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        $path = null;
        try {
            $file = $data['files'][0];

            $filename = Str::random(12) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('imports/scholars', $filename, 'public');

            Excel::import(new CheckScholarImport, storage_path('app/public/' . $path));


            ScholarUploadedFiles::create([
                'filename' => $filename,
                'filepath' => $path,
                'region_office' => Auth::user()->profile->agency_array['name'],
                'created_by' => Auth::user()->profile->fullname,
                'status' => 'pending'
            ]);


            $highTable = User::select('id')
                ->with('profile:user_id,fname,lname')
                ->whereHas('role', function ($q) {
                    $q->whereIn('name', ['Administrator']);
                })->where([
                    'is_active' => true,
                    'is_delete' => false,
                ])->get();


            foreach ($highTable as $admin) {
                $admin->notify(
                    new ScholarUploadedNotification(
                        Auth::user()->profile->fullname,
                        Auth::user()->profile->agency->name,
                    )
                );
            }

            DB::commit();

            return redirect()->back()->with('flash', [
                'status'  => 'success',
                'title'   => 'Excel Data save',
                'message' => 'Excel data save successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->back()->with('flash', [
                'status'  => 'error',
                'title'   => 'Import Failed',
                'message' => 'There was an error importing the data: ' . $e->getMessage(),
            ]);
        }
    }

    public function insert(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $file = ScholarUploadedFiles::where('id', Hashids::decode($id))->first();

            $file->update([
                'validated_by' => Auth::user()->profile->fullname,
                'validated_at' => now(),
                'status' =>   $request['status'],
            ]);
            $fullname = $file->created_by;
            $highTable = User::with('profile:user_id,fname,lname')
                ->whereHas('profile', function ($q) use ($fullname) {
                    $q->whereRaw("LOWER(CONCAT(fname, ' ', lname)) LIKE ?", ['%' . strtolower($fullname) . '%']);
                })
                ->select('id')
                ->get();


            foreach ($highTable as $admin) {
                $admin->notify(
                    new ValidatedFilesNotification(
                        $request['status'],
                        Auth::user()->profile->fullname,
                    )
                );
            }

            Excel::import(new ScholarImport, storage_path('app/public/' . $file->filepath));
            DB::commit();
            return redirect()->back()->with('flash', [
                'status'  => 'success',
                'title'   => 'Scholar Information Saved!',
                'message' => 'The scholar data has been successfully saved.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('flash', [
                'status'  => 'error',
                'title'   => 'Save Failed',
                'message' => 'There was an error saving the data: ' . $e->getMessage(),
            ]);
        }
    }
    // function update(StatusRequest $request, string $id, string $type)
    // {

    //     $data = $request->validated();
    //     $find = ListStatuses::findOrFail($id);

    //     if ($type == 'form') {
    //         $find->update([
    //             'name' => $data['name'],
    //             'icon' => $data['icon'],
    //             'type' => $data['type'],
    //             'color_id' => $data['color']['id'],
    //             'updated_by'    => Auth::user()->profile->fullname,
    //         ]);
    //     } else {
    //         $find->update([
    //             'is_active' => $data['isActive'],
    //         ]);
    //     }

    //     return redirect()->back()->with('flash', [
    //         'status' => 'success',
    //         'title'  => 'Status Updated',
    //         'message' => 'Status successfully updated.',
    //     ]);
    // }

    // function destroy(
    //     int $id
    // ) {
    //     $find = ListStatuses::findOrFail($id);
    //     $find->update([
    //         'is_delete' => true,
    //     ]);

    //     return redirect()->back()->with('flash', [
    //         'status' => 'success',
    //         'title'  => 'Status Deleted',
    //         'message' => 'Status successfully deleted.',
    //     ]);
    // }
}
