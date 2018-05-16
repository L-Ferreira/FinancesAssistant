<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function block($id)
    {
        $user = User::findOrFail($id);
        if ($user->blocked == 0)
        {
            $user->blocked = 1;
        }
        return view('home');
    }

    public function unblock($id)
    {

        $user = User::findOrFail($id);
        if ($user->admin == 1)
        {
            $user->admin = 0;
        }
        return view('home');
    }

    public function promote($id)
    {

        $user = User::findOrFail($id);
        if ($user->admin == 0)
        {
            $user->admin = 1;
        }
        return view('home');
    }

    public function demote($id)
    {
        $user = User::findOrFail($id);
        if ($user->admin == 1)
        {
            $user->admin = 0;
        }
        return view('home');
    }
}
