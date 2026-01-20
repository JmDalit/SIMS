<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ActivationController extends Controller
{
    public function show(string $token): Response
    {

        $user = User::with(['profile'])->where('activation_token', $token)->first();

        return Inertia::render('Auth/activationPage', [
            'token' => $token,
            'email' => $user->email
        ]);
    }
}
