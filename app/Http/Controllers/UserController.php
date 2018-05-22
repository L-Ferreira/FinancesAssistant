<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
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

        //sdd($request);

        $pageTitle = "List Users";
        $users = User::all();

        $name = $request->name;
        $type = $request->type;
        $status = $request->status;



        if($type == 'admin'){
            $type = 1;
        }


        if($type == 'normal'){
            $type = 0;
        }

        if($status == 'blocked'){
            $status = 1;

        }


        if($status == 'unblocked'){
            $status = 0;


        }

        if ($request->has('name')) {
            $users = User::query()->where('name', 'LIKE', '%' .$name . '%')->get();
        }


        if ($request->has('type')) {
            $users = User::query()->where('admin', 'LIKE', $type)->get();
        }

        if ($request->has('status')) {
            $users = User::query()->where('blocked', '=', $status)->get();
        }


        if ($request->has('type') && $request->has('name')) {
            $users= User::query()->where('admin','=',$type)->where('name', 'LIKE', '%' .$name . '%')->get();
        }


        if ($request->has('name') && $request->has('status')) {
            $users= User::query()->where('name','LIKE','%' .$name . '%')->where('blocked', '=', $status)->get();
        }

        if ($request->has('name') && $request->has('status') &&  $request->has('type')) {
            $users= User::query()->where('name','LIKE','%' .$name . '%')->where('blocked', '=', $status)->where('admin','=',$type)->get();
        }

        return view('showUsers', compact('pageTitle', 'users'));

    }

}
