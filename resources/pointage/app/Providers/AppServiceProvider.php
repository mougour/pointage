<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes(); // Ensures the api.php file is loaded
        $this->mapWebRoutes(); // Ensures the web.php file is loaded
        // Add more route mappings if needed
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are loaded by the RouteServiceProvider within a group
     * which contains the "api" middleware group. Now create something great!
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api') // Ensures the routes are prefixed with 'api'
             ->middleware('api') // Ensures the 'api' middleware is applied
             ->namespace($this->namespace) // Applies the correct controller namespace
             ->group(base_path('routes/api.php')); // Loads the api.php file
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are loaded by the RouteServiceProvider within a group
     * which contains the "web" middleware group. Now create something great!
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web') // Apply the 'web' middleware group
             ->namespace($this->namespace) // Applies the correct controller namespace
             ->group(base_path('routes/web.php')); // Loads the web.php file
    }
}
