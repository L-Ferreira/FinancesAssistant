<?php

namespace App\Http\Controllers;

use App\Rules\OldPassword;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\User;
use App\Accounts;
use App\Movements;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }


    public function showInitialStatistics(){

        $results_users= User::all()->count();

        $results_accounts= Accounts::all()->count();

        $results_movements= Movements::all()->count();


        return view('welcome',compact('results_users','results_accounts','results_movements'));
    }


    public function profile()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $this->authorize('edit', $user);
        return view('users.edit');
    }

    public function update(UpdateUserRequest $request)
    {
        //$this->authorize('edit', $user);
        $user = Auth::user();
        $this->authorize('edit', $user);
        $data = $request->validated();

        $user->fill($data);

        if ($request->hasFile('profile_photo')) {

            if ($user->profile_photo != null && $user->profile_photo != 'defaultpic.png') {
                Storage::delete('public/profiles/' . $user->profile_photo);
            }

            $user->profile_photo =  basename($request->file('profile_photo')->store('profiles', 'public'));
            $user->save();
        }

        if (!$request->has('phone')) {
            $user->phone = null;
        }

        $user->save();

        return redirect()
            ->route('me')
            ->with('success', 'User saved successfully');
    }

    public function editPassword(User $user)
    {
        $user = Auth::user();
        return view('users.password');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => [new OldPassword(), 'required'],
            'password' => 'required|string|min:3|confirmed',
        ]);

        $user = Auth::user();

        $password = bcrypt($request['password']);
        $user->password = $password;

        $user->save();

        return redirect()
            ->route('me')
            ->with('success', 'User saved successfully');

    }



}
