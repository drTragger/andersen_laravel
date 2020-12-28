<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Gate::define('update', function (User $user, $request) {
            return $user->id === $request->id;
        });

        Gate::define('get_user', function (User $user, $id) {
            return $user->id === $id;
        });

        Gate::define('get_users', function (User $user, $response){
            return $user->id === $response->id;
        });

        Gate::define('delete_user', function (User $user, $id){
            return $user->id === $id;
        });
    }
}
