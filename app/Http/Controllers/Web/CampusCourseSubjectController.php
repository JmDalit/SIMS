<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SchoolCampusCourseSubjects;
use Illuminate\Http\Request;

class CampusCourseSubjectController extends Controller
{
    function destroy(int $id)
    {


        $find = SchoolCampusCourseSubjects::findOrFail($id);
        $find->update([
            'is_delete' => true,
        ]);

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Subject Deleted',
            'message' => 'Subject successfully deleted.',
        ]);
    }
}
