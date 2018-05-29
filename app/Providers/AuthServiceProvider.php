<?php

namespace App\Providers;

use Ainet\Models\User;
use App\Accounts;
use App\Policies\AccountsPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

        'App\User' => 'App\Policies\UserPolicy',
        User::class => UserPolicy::class,
        Accounts::class => AccountsPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
