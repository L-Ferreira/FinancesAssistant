<?php

namespace App\Http\Controllers;

use App\AssociateMember;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function showUsers(Request $request)
    {
        $pageTitle = "List Users";

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

        $users = $query->paginate(10);

        return view('admin_showUsers', compact('pageTitle', 'users'));

    }

    public function showProfiles(Request $request) {

        $users = User::all();
        $associates = AssociateMember::all();

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

        $users = $query->paginate(10);

        return view('users_index', compact('users', 'associates'));
    }

}
