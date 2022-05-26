<?php
namespace App\Auth;

use Uworgws\Aclkit\RoleCheck;
use Illuminate\Support\ServiceProvider;

class UwAuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('UserProvider', function($app) {
            return new UserProvider();
        });
        $this->app->singleton('user', function($app) {
            return $app->make('UserProvider')->currentUser();
        });
        $this->app->singleton('acl', function($app) {
            return new RoleCheck(config('acl'), $app->make('UserProvider'));
        });
    }
}
