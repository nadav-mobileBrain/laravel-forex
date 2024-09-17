<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-signal', function ($user) {
            if ($user->permissions) {
                return in_array('manage-signal', $user->permissions);
            }return false;
        });
        Gate::define('manage-post', function ($user) {
            if ($user->permissions) {
                return in_array('manage-post', $user->permissions);
            }return false;
        });
        Gate::define('manage-user', function ($user) {
            if ($user->permissions) {
                return in_array('manage-user', $user->permissions);
            }return false;
        });
        Gate::define('manage-payment', function ($user) {
            if ($user->permissions) {
                return in_array('manage-payment', $user->permissions);
            }return false;
        });
        Gate::define('manage-withdraw', function ($user) {
            if ($user->permissions) {
                return in_array('manage-withdraw', $user->permissions);
            }return false;
        });
    }
}
