<?php

namespace App\Policies;

use App\Account;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountsPolicy
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

    public function delete(User $user, Account $account) {
        return $user->id == $account->owner_id;
    }

    public function close(User $user, Account $account) {
        return $user->id == $account->owner_id || $user->admin;
    }

    public function reopen(User $user, Account $account) {
        return $user->id == $account->owner_id || $user->admin;
    }

    public function createAccount(User $user) {
        return $user == Auth::user();
    }

    public function editAccount(User $user, Account $account) {
        return $user->id == $account->owner_id;
    }
}
