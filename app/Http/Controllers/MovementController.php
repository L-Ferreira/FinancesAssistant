<?php

namespace App\Http\Controllers;

use App\Account;
use App\Document;
use App\Http\Requests\CreateMovementRequest;
use App\Http\Requests\UpdateMovementRequest;
use App\Movement;
use App\Movement_category;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function PHPSTORM_META\type;

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

    public function create(User $user, Account $account) {
        $movement_types = Movement_category::all();
        if ($account->owner_id != Auth::user()->id) {
            return response(view('errors.403'), 403);
        }
        return view('movements.createMovement', compact('account','movement_types'));
    }

    public function store(CreateMovementRequest $request, Account $account) {

        //$this->authorize('createMovement', $account);
        if(Auth::user()->id != $account->owner_id){
            return response(view('errors.403'),403);
        }
        $data = $request->validated();

        $movement_types = Movement_category::find($data['movement_category_id']);

        $newMovement = new Movement();
        $newMovement->account_id = $account->id;
        $newMovement->movement_category_id = $data['movement_category_id'];
        $newMovement->date = $data['date'];
        $newMovement->value = $data['value'];
        $newMovement->start_balance = $data['value'];
        $newMovement->end_balance = $data['value'];
        $newMovement->description = $data['description'] ?? null;
        $newMovement->type = $movement_types->type;
        $newMovement->save();

        $account->last_movement_date = $data['date'];
        $account->save();


        /*
         *
         *  INSERIR CODIGO DO DOCUMENTO
         *
         *
        */



        return redirect()
            ->route('showAccounts', Auth::user())
            ->with('success', 'Movement created successfully');

    }

    public function edit($id) {
        $movement = Movement::findOrFail($id);
        $movement_types = Movement_category::all();
        $account = Account::findOrFail($movement->account_id);
        if(Auth::user()->id != $account->owner_id || $movement->account_id != $account->id){
            return response(view('errors.403'),403);
        }
        return view('movements.editMovement', compact('movement','movement_types'));
    }

    public function update(UpdateMovementRequest $request, Movement $movement) {


        $account = Account::findOrFail($movement->account_id);

        $data = $request->validated();

        $movement_types = Movement_category::find($data['movement_category_id']);

        $movement->movement_category_id = $data['movement_category_id'];
        $movement->date = $data['date'];
        $movement->value = $data['value'];
        $movement->description = $data['description'] ?? null;
        $movement->type = $movement_types->type;
        $movement->save();

        $account->last_movement_date = $data['date'];
        $account->save();



        /*
         *
         *  INSERIR CODIGO DO DOCUMENTO
         *
         *
        */



        return redirect()
            ->route('showAccounts', Auth::user())
            ->with('success', 'Movement created successfully');
    }

    public function destroy(Movement $movement){
//        $this->authorize('delete',$movement);
        $account = Account::findOrFail($movement->account_id);
//        $file = Document::findOrFail($movement->file_id);
        $count = Movement::query()->where('account_id','=',$movement->id)->count();

        if(Auth::user()->admin || Auth::user()->id == $account->owner_id ) {
            if ($count == 0) {
                return response(view('errors.404'), 404);
            } else {
                $movement->delete();
//                $file->delete();
            }
        }else {
            return response(view('errors.403'),403);
        }

        return redirect()->route('showAccounts', Auth::user())->with('success','User deleted successfully');
    }
}
