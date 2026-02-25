<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\GeolocationRequest;
use App\Imports\GeolocationImport;
use App\Models\GeolocationFiles;
use App\Models\geolocations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class GeolocationController extends Controller
{
    public function index()
    {
        return Inertia::render('Web/geolocationPage', [
            'locations' => geolocations::paginate(10)->toArray()
        ]);
    }

    public function store(GeolocationRequest $r)
    {
        $data = $r->validated();
        $file = $data['files'][0];
        try {

            DB::transaction(function () use ($file) {

                Excel::import(new GeolocationImport, $file);

                $path = $file->storeAs(
                    'imports/locations',
                    $file->getClientOriginalName(),
                    'public'
                );

                GeolocationFiles::create([
                    'filename' =>  $file->getClientOriginalName(),
                    'path' =>  $path,
                    'created_by' => Auth::user()->profile->fullname
                ]);
            });
            return redirect()->back()->with('flash', [
                'status'  => 'success',
                'title'   => 'Excel Data save',
                'message' => 'Excel data save successfully!',
            ]);
        } catch (\Throwable $e) {

            return redirect()->back()->with('flash', [
                'status'  => 'error',
                'title'   => 'Import Failed',
                'message' => 'There was an error importing the data: ' . $e->getMessage(),
            ]);
        }
    }
}
