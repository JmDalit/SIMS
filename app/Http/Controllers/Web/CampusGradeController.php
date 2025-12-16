<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CampusGradeRequest;
use App\Models\SchoolCampusGrades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampusGradeController extends Controller
{
    public function store(CampusGradeRequest $request)
    {
        $data = $request->validated();

        SchoolCampusGrades::create([
            'campus_id' => $data['campusId'],
            'grade' => $data['grade'],
            'lower' => $data['lower'],
            'upper' => $data['upper'],
            'is_failed' => $data['fail'],
            'is_incomplete' => $data['incomplete'],
            'created_by' => Auth::user()->profile->fullname
        ]);



        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Campus Grade Created',
            'message' => 'Campus grade successfully created.',
        ]);
    }


    public function update(CampusGradeRequest $request, string $id, string $type)
    {
        $data = $request->validated();
        $find = SchoolCampusGrades::findOrFail($id);

        if ($type == 'form') {

            $find->update([
                'grade' => $data['grade'],
                'lower' => $data['lower'],
                'upper' => $data['upper'],
                'is_failed' => $data['fail'],
                'is_incomplete' => $data['incomplete'],
                'created_by' => Auth::user()->profile->fullname
            ]);
        } else {
            $find->update([
                'is_active' => $data['isActive'],
            ]);
        }

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Campus Course Updated',
            'message' => 'Campus course successfully updated.',
        ]);
    }

    function destroy(int $id)
    {

        $find = SchoolCampusGrades::findOrFail($id);
        $find->update([
            'is_delete' => true,
        ]);

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Campus Course Deleted',
            'message' => 'Campus course successfully deleted.',
        ]);
    }
}
