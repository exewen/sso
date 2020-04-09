<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;
use App\Services\PermissionService;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::singleton('permission', function () {
            return new PermissionService();
        });
    }
}
