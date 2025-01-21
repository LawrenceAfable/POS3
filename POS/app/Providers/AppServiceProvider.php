<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    // Define the controller namespace
    protected $namespace = 'App\Http\Controllers';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Application service registration logic here
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load API routes
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace) // Use the defined namespace
            ->group(base_path('routes/api.php'));

        // Load Web routes
        Route::middleware('web')
            ->namespace($this->namespace) // Use the defined namespace
            ->group(base_path('routes/web.php'));
    }
}
