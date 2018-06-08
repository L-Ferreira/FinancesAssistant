<?php

namespace App\Policies;

use App\Account;
use App\Document;
use App\Movement;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
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

    public function deleteDocument(Movement $movement, Account $account) {
        return $account->id == $movement->account_id;
    }
}
