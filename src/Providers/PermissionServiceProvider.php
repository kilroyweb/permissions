<?php

namespace KilroyWeb\Permissions\Providers;

use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \KilroyWeb\Permissions\Commands\MakePermission::class,
            ]);
        }
        //
        $this->publishes([
            __DIR__.'/../Configuration/Templates/permissions.php' => config_path('permissions.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
