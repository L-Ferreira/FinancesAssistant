<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Movement;
use App\Movements;
use App\User;
use function Couchbase\defaultDecoder;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;
use function Tests\Feature\to_cents;


class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showAccounts($id){
        $pageTitle = "List my Account";
        $user = User::find($id);
        $count = User::query()->where('id','=',$id)->count();
        if($count > 0){
//            if(Auth::user()->admin){
//                $accounts = Account::join('account_types','account_types.id','=','accounts.account_type_id')
//                    ->select('accounts.*', 'account_types.name')->get();
//            }else{
                $accounts = Account::join('account_types','account_types.id','=','accounts.account_type_id')
                    ->select('accounts.*', 'account_types.name')
                    ->where('accounts.owner_id','=',$user->id)->paginate(10);
//            }
        }else{
            return response(view('errors.404'),404);
        }

        if($user->id == Auth::user()->id || Auth::user()->isAssociateOf($user)) {
            return view('accounts.account_index', compact('pageTitle', 'accounts'));
        }else{
            return response(view('errors.403'),403);
        }
    }

    public function showAccountsOpened($user){
        $pageTitle = "List my Account Closed" ;

        $count = User::query()->where('id','=',$user)->count();

        if($count > 0){
            $accounts = Account::join('account_types','account_types.id','=','accounts.account_type_id')
                ->select('accounts.*','account_types.name')
                ->where('accounts.owner_id','=',$user)
                ->where('accounts.deleted_at','=',NULL)->paginate(10);
        }else{
            return response(view('errors.404'),404);
        }

        if($user == Auth::user()->id) {
            return view('accounts.account_index', compact('pageTitle', 'accounts'));
        }else{
            return response(view('errors.403'),403);
        }
    }

    public function showAccountsClosed($user){
        $pageTitle = "List my Account Closed" ;

        $count = User::query()->where('id','=',$user)->count();

        if($count > 0){
            $accounts = Account::join('account_types','account_types.id','=','accounts.account_type_id')
                ->select('accounts.*','account_types.name')
                ->where('accounts.owner_id','=',$user)
                ->where('accounts.deleted_at','!=',NULL)->paginate(10);
        }else{
            return response(view('errors.404'),404);
        }

        if($user == Auth::user()->id) {
            return view('accounts.account_index', compact('pageTitle', 'accounts'));
        }else{
            return response(view('errors.403'),403);
        }
    }

    public function destroy(Account $account){
        $this->authorize('delete',$account);

        $count = Movement::query()->where('account_id','=',$account->id)->count();

        if(!Auth::user()->admin || Auth::user()->admin) {
            if ($count == 0 && is_null($account->last_movement_date)) {

                $account->delete();
            } else {
                return response(view('errors.403'), 403);
            }
        }else {
            return response(view('errors.403'),403);

        }

        return redirect()->route('showAccounts', Auth::user())->with('success','User deleted successfully');
    }

    public function close(Account $account){
        $this->authorize('close',$account);

        $count = Account::query()->where('id','=',$account->id)->count();
        if($account->owner_id == Auth::user()->id) {
            if ($count > 0) {
                $account->deleted_at = Carbon::now();
            } else {
                return response(view('errors.404'), 404);
            }

            $account->save();
        }else {
            return response(view('errors.403'),403);

        }

        return redirect()->route('showAccounts', Auth::user())->with('success','Account closed');
    }

    public function reopen(Account $account){
        $this->authorize('reopen',$account);
        $count = Account::query()->where('id','=',$account->id)->count();

        if($account->owner_id == Auth::user()->id){
            if($count > 0){
                $account->deleted_at = null;
            }else{
                return response(view('errors.404'),404);
            }

            $account->save();
        } else {
            return response(view('errors.403'),403);

        }



        return redirect()->route('showAccounts', Auth::user())->with('success','Account reopen');
    }
    
    public function create() {
        //$this->authorize('createAccount', $user);

        return view('accounts.account_form', compact('request'));
    }

    public function store(CreateAccountRequest $request, User $user) {

//        $this->authorize('createAccount', $user);

        $data = $request->validated();

        $newAccount = new Account();
        $newAccount->fill($data);
        $newAccount->owner_id = Auth::user()->id;
       // $newAccount->account_type_id = $data['account_type_id'];
        $newAccount->date = $data['date'] ?? Carbon::now() ->format('Y-m-d');
      //  $newAccount->code = $data['code'];
        $newAccount->description = $data['description'] ?? null;
       // $newAccount->start_balance = $data['start_balance'];
        $newAccount->current_balance = $data['start_balance'];
    //    $newAccount->last_movement_date = null;
     //   $newAccount->deleted_at = null;
        $newAccount->save();

        return redirect()
            ->route('me')
            ->with('success', 'Account created successfully');
    }

    public function edit($id)
    {
//        $user = Auth::user();
//        dd($user);
//        $this->authorize('edit', $user);
        $account = Account::find($id);
        return view('accounts.edit', compact('account'));
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $this->authorize('editAccount',$account);
        $data = $request->validated();

        if (is_null($account->last_movement_date)) {
            $account->current_balance = $data['start_balance'];
        } else {
            //$account->current_balance = $account->current_balance+($data['start_balance'] - $account->start_balance);
            $movements = Movement::query()->where('account_id', '=', $account->id)->orderBy('created_at')->get();

            $account->current_balance = $this->recalculateMovementsBalance($movements, $data['start_balance']);
        }

        $account->owner_id = Auth::user()->id;
        $account->account_type_id = $data['account_type_id'];
        $account->date = $data['date'] ?? Carbon::now()->format('Y-m-d');
        $account->code = $data['code'];
        $account->description = $data['description'] ?? null;
        $account->start_balance = $data['start_balance'];

        $account->save();

        return redirect()
            ->route('showAccounts', Auth::user())
            ->with('success', 'User saved successfully');
    }

    private function recalculateMovementsBalance($movements, $startBalance)
    {
        $movs_end_balance = $startBalance;
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
}



