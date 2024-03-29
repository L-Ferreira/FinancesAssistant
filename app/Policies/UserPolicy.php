<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function isAdmin(User $user)
    {
        return $user->admin == 1;
    }

    public function edit(User $user, User $userToEdit) {
        return $user->id === $userToEdit->id;
    }
}
