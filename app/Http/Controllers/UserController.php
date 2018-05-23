<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\User;
use App\Accounts;
use App\Movements;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
//        $this->authorize('edit', $user);
        $user = Auth::user();
        return view('users.edit');
    }

    public function update(UpdateUserRequest $request)
    {
        //$this->authorize('edit', $user);
        $user = Auth::user();
        $data = $request->validated();

        $user->fill($data);

        if ($request->hasFile('profile_photo')) {

            if ($user->profile_photo != null && $user->profile_photo != 'defaultpic.png') {
                Storage::delete('public/profiles/' . $user->profile_photo);
            }

            $user->profile_photo =  basename($request->file('profile_photo')->store('profiles', 'public'));
            $user->save();
        }

        $user->save();

        return redirect()
            ->route('me')
            ->with('success', 'User saved successfully');
    }

    public function editPassword(User $user)
    {
        $user = Auth::user();
        return view('users.password', compact('user'));
    }

    public function updatePassword(UpdateUserRequest $request, User $user)
    {
        $this->authorize('edit', $user);

        $data = $request->validated();

        $user->fill($data);
        $user->save();

        return redirect()
            ->route('me')
            ->with('success', 'User saved successfully');
    }

    public function showUsers(Request $request)
    {



        $pageTitle = "List Users";
        $users = User::all();

        $name = $request->name;
        $type = $request->type;
        $status = $request->status;

        $query = User::query();

        if($type == 'admin'){
            $query = $query->where('admin',true);
        }else if($type == 'normal'){
            $query = $query->where('admin',false);
        }

        if($status == 'blocked'){
            $query = $query->where('blocked',true);
        }else if($status == 'unblocked'){
            $query = $query->where('blocked',false);
        }


        if ($request->has('name')) {
            $query = $query->where('name', 'LIKE', '%' .$name . '%');
        }

        $users = $query->get();

        return view('showUsers', compact('pageTitle', 'users'));

    }

}
