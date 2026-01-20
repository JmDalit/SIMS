<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\UserRequest;
use App\Mail\UserCreatedMail;
use App\Models\User;
use App\References\ListClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Str;

class UserController extends Controller
{
    function index(Request $request, ListClass $reference)
    {
        $user = User::where('is_delete', false)
            ->when($request->input('search'), function ($query) {
                $search = strtolower(request('search'));

                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                        ->orWhereHas('profile', function ($q2) use ($search) {
                            $q2->whereRaw('LOWER(fname) LIKE ?', ["%{$search}%"])
                                ->orWhereRaw('LOWER(lname) LIKE ?', ["%{$search}%"]);
                        });
                });
            })
            ->with(['role', 'profile'])->orderBy('id')
            ->paginate(10);


        return Inertia::render('Web/userPage', [
            'users' => $user,
            'roleOption' => $reference->getRoles(false)
        ]);
    }


    function store(UserRequest $request)
    {

        $data = $request->validated();
        $activation = Str::random(64);


        $user = User::create([
            'email'             => $data['email'],
            'role_id'           => $data['role']['id'],
            'can_create'        => $data['canCreate'],
            'can_edit'          => $data['canEdit'],
            'activation_token'  => $activation,
            'can_delete'        => $data['canDelete'],
            'is_active'         => false
        ]);

        $user->profile()->create([
            'fname' =>  Str::lower($data['fname']),
            'lname' => Str::lower($data['lname'])
        ]);

        Mail::to($data['email'])->send(new UserCreatedMail($user, $activation));


        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'User Created',
            'message' => 'User successfully created.',
        ]);
    }

    function update(UserRequest $request, string $id, string $type)
    {

        $data = $request->validated();
        $find = User::findOrFail($id);



        switch ($type) {
            case 'create':
                $find->update([
                    'can_create' => $data['canCreate'],
                ]);
                break;
            case 'edit':
                $find->update([
                    'can_edit' => $data['canEdit'],
                ]);
                break;
            case 'delete':
                $find->update([
                    'can_delete' => $data['canDelete'],
                ]);
                break;
            case 'status':
                $find->update([
                    'is_active' => $data['isActive'],
                ]);
                break;

            default:
                $find->update([
                    'email'         => $data['email'],
                    'role_id'       => $data['role']['id'],
                    'can_create'    => $data['canCreate'],
                    'can_edit'      => $data['canEdit'],
                    'can_delete'    => $data['canDelete'],
                ]);
                $find->profile()->update([
                    'fname' => $data['fname'],
                    'lname' => $data['lname']
                ]);
                break;
        }


        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'User Updated',
            'message' => 'User successfully updated.',
        ]);
    }



    function destroy(UserRequest $request, int $id)
    {
        dd($request->checkSuperAdminId());

        $find = User::findOrFail($id);
        $find->update([
            'is_delete' => true,

        ]);
        return redirect()->back()->with('flash', [
            'status' => 'success',
            'title'  => 'User Deleted',
            'message' => 'User successfully deleted.',
        ]);
    }
}
