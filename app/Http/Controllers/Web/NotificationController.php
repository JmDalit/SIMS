<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = auth('web')->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back();
    }
}
