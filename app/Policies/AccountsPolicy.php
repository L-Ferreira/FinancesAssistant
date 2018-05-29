<?php

namespace App\Policies;

use App\Accounts;
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

    public function delete(User $user, Accounts $account){
        return $user->id === $account->owner_id;
    }

    public function close(User $user, Accounts $account){
        return $user->id === $account->owner_id || $user->admin;
    }

    public function reopen(User $user, Accounts $account){
        return $user->id === $account->owner_id || $user->admin;
    }
}
