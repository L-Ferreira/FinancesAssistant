<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function showInitialStatistics(){

        $results_users= User::all()->count();

        $results_accounts= DB::table('accounts')->count();

        $results_movements= DB::table('movements')->count();


        return view('welcome',compact('results_users','results_accounts','results_movements'));
    }

    public function showUsers()
    {
        $pagetitle = "List Users";
        $users = User::all();
        return view('showUsers', compact('pagetitle', 'users'));
    }
}
