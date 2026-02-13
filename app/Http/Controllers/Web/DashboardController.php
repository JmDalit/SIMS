<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SchoolCampuses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {

        return Inertia::render('Web/dashboardPage', [
            'campus_cnt' => SchoolCampuses::when(Auth::check() && Auth::user()->role_array['name'] != 'Administrator', function ($q) {
                $q->where('agency_id', Auth::user()->profile->agency_id);
            })->count(),
            'campuses_details' => Auth::user()->role_array['name'] != 'Administrator' && SchoolCampuses::where(['agency_id' => Auth::user()->profile->agency_id, 'is_delete' => false])->exists() ? SchoolCampuses::select('id', 'generated_name', 'school_id', 'name')->where(['agency_id' => Auth::user()->profile->agency_id, 'is_delete' => false])
                ->with([
                    'school' => fn($q) => $q
                        ->select('id', 'shortcut')
                        ->where('is_delete', false),
                    'address',
                    'semesters' => fn($q) => $q
                        ->select('id', 'semester_id', 'campus_id', 'start_date', 'end_date', 'submission_date')
                        ->whereDate('start_date', '<=', now())
                        ->whereDate('end_date', '>=', now()),
                ])
                ->withCount('courses')
                ->get()
                ->map(function ($campus) {
                    return [
                        'id' => $campus->id,
                        'generated_name' => $campus->generated_name,
                        'school_shortcut' => $campus->school
                            ? (
                                $campus->name
                                ? $campus->school->shortcut . '-' . $campus->name
                                : $campus->school->shortcut . '-' . $campus->address?->municipality_array['name']
                            )
                            : null,
                        'program_cnt' => $campus->courses_count,
                        'grading_status' => $campus->grades()->exists(),
                        'semesters' => $campus->semesters->isNotEmpty()
                            ? [
                                'start_date' => Carbon::parse($campus->semesters[0]->start_date)->format('M Y'),
                                'end_date'   => Carbon::parse($campus->semesters[0]->end_date)->format('M Y'),
                                'type'   => $campus->semesters[0]->semester_array,
                                'submission_date'   => Carbon::parse($campus->semesters[0]->submission_date)->format('M d, Y'),
                            ]
                            : [],
                        'tset' => $campus->address?->municipality_array['name']
                    ];
                }) : null
        ]);
    }
}
