<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests\CreateMovementRequest;
use App\Http\Requests\UpdateMovementRequest;
use App\Movement;
use App\Movement_category;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function accountMovements(Account $account)
    {

        $pageTitle = "List of Movements";

        if(Auth::user()->id != $account->owner_id){
            return response(view('errors.403'),403);
        }

        $count = Movement::query()->where('account_id','=',$account->id)->count();

        if($count > 0)
        {
//            $movements = Movements::query()->where('account_id', '=', $account->id)->orderByDesc('created_at')->get();
            $movements = Movement::query()->where('account_id', '=', $account->id)->orderByDesc('date')->get();

            $movements = Movement::join('movement_categories','movement_categories.id','=','movements.movement_category_id')
                ->select('movements.*', 'movement_categories.name')
                ->where('movements.account_id','=',$account->id)
                ->orderByDesc('movements.date')->get();
        } else {
            return response(view('errors.404'),404);

        }


        return view('movements.movements_index', compact('pageTitle', 'movements'));
    }

    public function create(Account $account) {
//        $this->authorize('createAccount', $user);
        $movement_types = Movement_category::all();
        return view('movements.createMovement', compact('account','movement_types'));
    }

    public function store(CreateMovementRequest $request, Account $account) {

    }

    public function edit($id) {
    }

    public function update(UpdateMovementRequest $request, Movement $movement) {
    }
}
