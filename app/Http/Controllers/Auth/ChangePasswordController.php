<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChangePasswordController extends Controller
{
    public function update(ChangePasswordRequest $request)
    {
        $user = $request->user();
        $user->password = bcrypt($request->input('new'));
        $user->save();

        Inertia::clearHistory();
        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'Password Changed',
            'message' => 'Password successfully changed.',
        ])->with('inertia', ['clearHistory' => true]);
    }
}
