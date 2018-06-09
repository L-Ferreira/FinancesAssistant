<?php

namespace App\Http\Controllers;

use App\Account;
use App\Document;
use App\Http\Requests\CreateMovementRequest;
use App\Http\Requests\DocumentRequest;
use App\Http\Requests\UpdateMovementRequest;
use App\Movement;
use App\Movement_category;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use function PHPSTORM_META\type;
use PhpParser\Comment\Doc;

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


        return view('movements.movements_index', compact('pageTitle', 'movements','account'));
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

        $new_mov_balance = Movement::query()->where([['account_id', '=', $account->id],['date', '<=', $data['date']]])->latest()->first();
        $newMovement = new Movement();
        $newMovement->account_id = $account->id;
        $newMovement->movement_category_id = $data['movement_category_id'];
        $newMovement->date = $data['date'];
        $newMovement->value = $data['value'];
        if ($new_mov_balance != null) {
            $newMovement->start_balance = $new_mov_balance->end_balance;
            $newMovement->end_balance = $new_mov_balance->end_balance+$data['value'];
        } else {
            $newMovement->start_balance = 0;
            $newMovement->end_balance =  $newMovement->start_balance+$data['value'];
        }
        $newMovement->description = $data['description'] ?? null;
        $newMovement->type = $movement_types->type;
        $newMovement->save();

        if($request->hasFile('document_file') && $request->file('document_file')->isValid()){
            $type = $data['document_file']->getClientOriginalExtension();
            $document = new Document();
            $document->type =$type;
            $document->original_name = $request->file('document_file')->getClientOriginalName();
            $document->description = $data['document_description'] ?? null;

            Storage::putFileAs('documents/'.$newMovement->account_id, $request->file('document_file'), $newMovement->id.'.'.$type);

            $document->save();

            $newMovement->document_id = $document->id ?? null;
            $newMovement->save();
        }

        $movements_to_recalculate = Movement::query()->where([['account_id', '=', $account->id],['date', '>=', $data['date']]])->orderBy('date')->get();

        $account->current_balance = $this->recalculateMovementsBalance($movements_to_recalculate,($newMovement->end_balance));
        $account->last_movement_date = $data['date'];
        $account->save();

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

        if($request->hasFile('document_file') && $request->file('document_file')->isValid()){

            $type = $data['document_file']->getClientOriginalExtension();
            if ($movement->document_id != null) {
                $document = Document::find($movement->document_id);
                Storage::delete('documents/'.$movement->account_id.'/'.$movement->id.'.'.$document->type,$document->original_name);
            } else {
                $document = new Document();
            }
            $document->original_name = $request->file('document_file')->getClientOriginalName();
            $document->description = $data['document_description'] ?? null;
            $document->type = $type;
            Storage::putFileAs('documents/'.$movement->account_id, $request->file('document_file'), $movement->id.'.'.$type);

            $document->save();

            $movement->document_id = $document->id ?? null;
        }

        $movement->save();

        $account->last_movement_date = $data['date'];
        $account->save();


        return redirect()
            ->route('showAccounts', Auth::user())
            ->with('success', 'Movement created successfully');
    }

    private function recalculateMovementsBalance($movements, $value)
    {
        $movs_end_balance = $value;

        foreach ($movements as $movement) {
            $movement->start_balance = $movs_end_balance;
            $movement->start_balance = ($this->to_cents($movement->start_balance))/100;
            if ($movement->type == "expense") {
                $movement->end_balance = $movement->start_balance - $movement->value;
            } else {
                $movement->end_balance = $movement->start_balance + $movement->value;
            }
            $movs_end_balance = $movement->end_balance;
            $movement->end_balance = ($this->to_cents($movement->end_balance))/100;
            $movement->save();
        }
        return $movs_end_balance;

    }

    private function to_cents($value)
    {
        return bcmul($value, 100, 0);
    }

    public function destroy(Movement $movement){
//        $this->authorize('delete',$movement);
        $account = Account::findOrFail($movement->account_id);

      
        if(Auth::user()->admin || Auth::user()->id == $account->owner_id ) {
            if ($movement->document_id != null) {
                $document = Document::find($movement->document_id);
                $document->delete();
                Storage::delete('documents/'.$movement->account_id.'/'.$movement->id.'.'.$document->type,$document->original_name);
            }
            $movement->delete();
        }else {
            return response(view('errors.403'),403);
        }

        return redirect()->route('showAccounts', Auth::user())->with('success','User deleted successfully');
    }
}
