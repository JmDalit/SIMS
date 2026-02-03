<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ScholarRequest;
use App\Imports\CheckScholarImport;
use App\Imports\ScholarImport;
use App\Models\ScholarUploadedFiles;
use App\References\ScholarClass;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Vinkla\Hashids\Facades\Hashids;

class ScholarController extends Controller
{
    public function index(ScholarClass $scholar, Request $request)
    {
        return Inertia::render('Web/scholarPage', [
            'scholar' => [],
            'scholarDetails' => [],
            'history' => request('fileStatus') ? ScholarUploadedFiles::select([
                'id',
                'filename',
                'created_by',
                'created_at',
                'validated_by',
                'validated_at'
            ])
                ->latest()
                ->get()
                ->map(fn($file) => [
                    'id' => Hashids::encode($file->id),
                    'filename' => $file->filename,
                    'created_by' => $file->created_by,
                    'created_at' => $file->created_at,
                    'validated_by' => $file->validated_by,
                    'validated_at' => $file->validated_at,
                ]),
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

            $filename = Hashids::encode(time()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('imports/scholars', $filename, 'public');

            Excel::import(new CheckScholarImport, storage_path('app/public/' . $path));


            ScholarUploadedFiles::create([
                'filename' => $filename,
                'filepath' => $path,
                'region_office' => Auth::user()->profile->agency_array['name'],
                'created_by' => Auth::user()->profile->fullname,
            ]);

            // Excel::import(new ScholarImport, storage_path('app/public/' . $path));

            DB::commit();

            return redirect()->back()->with('flash', [
                'status'  => 'success',
                'title'   => 'Excel Data Imported',
                'message' => 'Excel data imported successfully!',
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
