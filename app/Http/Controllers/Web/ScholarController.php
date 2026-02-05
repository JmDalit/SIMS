<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ScholarRequest;
use App\Imports\CheckScholarImport;
use App\Imports\ScholarImport;
use App\Models\ScholarUploadedFiles;
use App\Models\User;
use App\Notifications\ScholarUploadedNotification;
use App\Notifications\ValidatedFilesNotification;
use App\References\ScholarClass;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Vinkla\Hashids\Facades\Hashids;

use function Symfony\Component\Clock\now;

class ScholarController extends Controller
{
    public function index()
    {
        return Inertia::render('Web/scholarPage', [
            'scholar' => [],
            'scholarDetails' => [],
            'files' => request('OpenFiles')
                ? ScholarUploadedFiles::When(request('status'), function ($item) {})->latest()->paginate(4)
                : null,
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
