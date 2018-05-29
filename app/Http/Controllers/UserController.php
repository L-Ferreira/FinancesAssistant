<?php

namespace App\Http\Controllers;

use App\AssociateMembers;
use App\Rules\OldPassword;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Access\Gate;
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
//use Intervention\Image\Facades\Image;

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


    public function myProfile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function usersProfile($id)
    {
        $user = User::find($id);
        return view('profile', compact('user'));
    }

    public function edit()
    {
//        $user = Auth::user();
//        dd($user);
//        $this->authorize('edit', $user);
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


    public function associates()
    {
        $user = Auth::user();
        $users = DB::table('users')
            ->join('associate_members', 'users.id', '=', 'associate_members.associated_user_id')
            ->select('users.*')
            ->where([['associate_members.main_user_id', '=', $user->id]])
            ->get();
        return view('associates', compact('user', 'users'));
    }

    public function associatesOf()
    {
       $user = Auth::user();
       $users = DB::table('users')
            ->join('associate_members', 'users.id', '=', 'associate_members.main_user_id')
            ->select('users.*')
            ->where([['associate_members.associated_user_id', '=', $user->id]])
            ->get();
       return view('associates', compact('user', 'users'));
    }

    public function block($id)
    {
        //$this->authorize('deny', $id);
        $user = User::findOrFail($id);

        if ($user->blocked == 0)
        {
                $user->blocked = 1;
        }
        $user->save();
        return redirect()->back()->with('success', 'User blocked with success!');

    }

    public function unblock($id)
    {

        $user = User::findOrFail($id);
        if ($user->blocked == 1)
        {
            $user->blocked = 0;
        }
        $user->save();
        return redirect()->back()->with('success', 'User unblocked with success!');
    }

    public function promote($id)
    {
        $user = User::findOrFail($id);
        if ($user->admin == 0)
        {
            $user->admin = 1;
        }
        $user->save();
        return redirect()->back()->with('success', 'User promoted with success!');
    }

    public function demote($id)
    {
        $user = User::findOrFail($id);
        if ($user->admin == 1)
        {
            $user->admin = 0;
        }
        $user->save();
        return redirect()->back()->with('success', 'User demoted with success!');
    }

}
