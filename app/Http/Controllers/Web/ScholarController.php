<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\References\ScholarClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ScholarController extends Controller
{
    public function index(ScholarClass $scholar, Request $request)
    {
        return Inertia::render('Web/scholarPage', [
            'scholar' => $scholar->getScholar(),
            'scholarDetails' => $scholar->getGrades('U-2025-01-00001')
        ]);
    }

    public function details(string $spas_no) {}

    public function store(StatusRequest $request)
    {
        $data = $request->validated();

        ListStatuses::create([
            'name' => $data['name'],
            'icon' => $data['icon'],
            'type' => $data['type'],
            'color_id' => $data['color']['id'],
            'created_by'    => Auth::user()->profile->fullname
        ]);

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Status Created',
            'message' => 'Status successfully created.',
        ]);;
    }
    function update(StatusRequest $request, string $id, string $type)
    {

        $data = $request->validated();
        $find = ListStatuses::findOrFail($id);

        if ($type == 'form') {
            $find->update([
                'name' => $data['name'],
                'icon' => $data['icon'],
                'type' => $data['type'],
                'color_id' => $data['color']['id'],
                'updated_by'    => Auth::user()->profile->fullname,
            ]);
        } else {
            $find->update([
                'is_active' => $data['isActive'],
            ]);
        }

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Status Updated',
            'message' => 'Status successfully updated.',
        ]);
    }

    function destroy(
        int $id
    ) {
        $find = ListStatuses::findOrFail($id);
        $find->update([
            'is_delete' => true,
        ]);

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Status Deleted',
            'message' => 'Status successfully deleted.',
        ]);
    }
}
