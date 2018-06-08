<?php

namespace App\Policies;

use App\Account;
use App\Movement;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class MovementPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, Account $account, Movement $movement) {
        return $user->id == $account->owner_id && $account->id == $movement->account_id;
    }

    public function close(User $user, Account $account, Movement $movement) {
        return $user->id == $account->owner_id || $user->admin ;
    }

    public function reopen(User $user, Account $account, Movement $movement) {
        return $user->id == $account->owner_id || $user->admin;
    }

    public function createMovement(Account $account) {
        return Auth::user()->id == $account->owner_id;
    }

    public function editMovement(User $user, Account $account, Movement $movement) {
        return $user->id == $account->owner_id && $account->id == $movement->account_id;
    }
}
