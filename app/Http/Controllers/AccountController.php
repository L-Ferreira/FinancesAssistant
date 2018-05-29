<?php

namespace App\Http\Controllers;

use App\Accounts;
use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


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

        $account->delete();

        return redirect()->route('showAccounts', Auth::user())->with('success','User deleted successfully');
    }

    public function close(Accounts $account){
        $this->authorize('close',$account);

        $count = Accounts::query()->where('id','=',$account->id)->count();

        if($count > 0){
            $account->deleted_at = Carbon::now();
        }else{
            return response(view('errors.404'),404);
        }

        $account->save();

        return redirect()->route('showAccounts', Auth::user())->with('success','Account closed');
    }

    public function reopen(Accounts $account){
        $this->authorize('reopen',$account);

        $count = Accounts::query()->where('id','=',$account->id)->count();

        if($count > 0){
            $account->deleted_at = null;
        }else{
            return response(view('errors.404'),404);
        }

        $account->save();

        return redirect()->route('showAccounts', Auth::user())->with('success','Account reopen');
    }
}
