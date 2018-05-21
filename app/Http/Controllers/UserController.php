<?php

namespace App\Http\Controllers;

use App\Accounts;
use App\Movements;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function showInitialStatistics(){

        $results_users= User::all()->count();

        $results_accounts= Accounts::all()->count();

        $results_movements= Movements::all()->count();


        return view('welcome',compact('results_users','results_accounts','results_movements'));
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
