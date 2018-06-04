<?php

namespace App\Http\Controllers;

use App\Account;
use App\Movement;
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


        return view('movements', compact('pageTitle', 'movements'));
    }

    public function createMovement($account)
    {
        $pagetitle = "Create Movement";


        $movements->account_id = $account;
        $type = htmlspecialchars($_POST['type']);
        $movement_category_id = htmlspecialchars($_POST['movement_category_id']);
        $date = htmlspecialchars($_POST['date']);
        $value = htmlspecialchars ($_POST['value']);
        $movements =  Movement::create(['account_id' => $account, 'type' => $type, 'movement_category_id' => $movement_category_id, 'date' => $date, 'value' => $value]);

        $movements->save();
        $insertedID  = $movements->id;

        return view('createMovement', compact('pagetitle'));

    }

    public function editMovement($movement)
    {
        $pagetitle = "Edit Movement";

        /*$movements = Movements::query()->where('id', '=', $movement)->update(['type' =]);*/

        //$movements->save();
        return view('editMovement', compact('pagetitle'));
    }

    public function deleteMovement($movement)
    {
        $movement = Movement::find($movement);
        $movement->delete();

        return redirect()->back();
    }
}
