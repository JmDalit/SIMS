<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SchoolCampusInfoRequest;
use App\Models\SchoolCampusInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolCampusInfoController extends Controller
{
    public function store(SchoolCampusInfoRequest $request)
    {
        $data = $request->validated();

        SchoolCampusInfo::create([
            'campus_id' => $data['campusId'],
            'dean' => $data['dean'],
            'registrar' => $data['registrar'],
            'contact' => $data['contact'],
            'email' => $data['email'],
            'created_by' => Auth::user()->profile->fullname,
        ]);

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'School Detail Created',
            'message' => 'School detail successfully created.',
        ]);
    }

    public function update(SchoolCampusInfoRequest $request, string $id, string $type)
    {
        $data = $request->validated();

        $campusInfo = SchoolCampusInfo::find($id);

        switch ($type) {
            case 'status':
                $campusInfo->update([
                    'is_active' => $data['isActive'],
                    'updated_by' => Auth::user()->profile->fullname,
                ]);
                $message = 'School campus info status successfully updated.';
                break;
            case 'delete':
                $campusInfo->update([
                    'is_delete' => $data['isDelete'],
                    'updated_by' => Auth::user()->profile->fullname,
                ]);
                $message = 'School campus info successfully deleted.';
                break;
            default:
                $campusInfo->update([
                    'dean' => $data['dean'],
                    'registrar' => $data['registrar'],
                    'contact' => $data['contact'],
                    'email' => $data['email'],
                    'updated_by' => Auth::user()->profile->fullname,
                ]);
                $message = 'School campus info successfully updated.';
                break;
        }

        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'School Campus Info Updated',
            'message' => $message,
        ]);
    }
}
