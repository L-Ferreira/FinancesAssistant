<?php

namespace App\Http\Controllers;

use App\Accounts;
use App\Movements;
use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use function PHPSTORM_META\type;


class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showAccounts($user){
        $pageTitle = "List my Accounts";



        $count = User::query()->where('id','=',$user)->count();

        if($count > 0){
            $accounts = Accounts::join('account_types','account_types.id','=','accounts.account_type_id')
                ->select('accounts.*', 'account_types.name')
                ->where('accounts.owner_id','=',$user)->get();
        }else{
            return response(view('errors.404'),404);
        }

        if($user == Auth::user()->id) {
            return view('accounts', compact('pageTitle', 'accounts'));
        }else{
            return response(view('errors.403'),403);
        }
    }

    public function showAccountsOpened($user){
        $pageTitle = "List my Accounts Closed" ;

        $count = User::query()->where('id','=',$user)->count();

        if($count > 0){
            $accounts = Accounts::join('account_types','account_types.id','=','accounts.account_type_id')
                ->select('accounts.*','account_types.name')
                ->where('accounts.owner_id','=',$user)
                ->where('accounts.deleted_at','=',NULL)->get();
        }else{
            return response(view('errors.404'),404);
        }

        if($user == Auth::user()->id) {
            return view('accounts', compact('pageTitle', 'accounts'));
        }else{
            return response(view('errors.403'),403);
        }
    }

    public function showAccountsClosed($user){
        $pageTitle = "List my Accounts Closed" ;

        $count = User::query()->where('id','=',$user)->count();

        if($count > 0){
            $accounts = Accounts::join('account_types','account_types.id','=','accounts.account_type_id')
                ->select('accounts.*','account_types.name')
                ->where('accounts.owner_id','=',$user)
                ->where('accounts.deleted_at','!=',NULL)->get();
        }else{
            return response(view('errors.404'),404);
        }

        if($user == Auth::user()->id) {
            return view('accounts', compact('pageTitle', 'accounts'));
        }else{
            return response(view('errors.403'),403);
        }
    }

    public function destroy(Accounts $account){
        $this->authorize('delete',$account);

        $count = Movements::query()->where('account_id','=',$account->id)->count();

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

    public function close(Accounts $account){
        $this->authorize('close',$account);

        $count = Accounts::query()->where('id','=',$account->id)->count();
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

    public function reopen(Accounts $account){
        $this->authorize('reopen',$account);

        $count = Accounts::query()->where('id','=',$account->id)->count();

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

    public function accountMovements($account)
    {
        $pageTitle = "List of Accounts";


        if(Movements::query()->where('account_id', '=', $account))
        {
            $movements = Movements::all();
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
        $movements =  Movements::create(['account_id' => $account, 'type' => $type, 'movement_category_id' => $movement_category_id, 'date' => $date, 'value' => $value]);

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
        $movement = Movements::find($movement);
        $movement->delete();

        return redirect()->back();
    }
}
