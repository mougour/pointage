<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\CorsMiddleware::class, // Add this line
        // Other middleware...
    ];
    protected $middlewareGroups = [
        'api' => [
            EnsureFrontendRequestsAreStateful::class, // Add this line
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
    protected $commands = [
        // You can register custom artisan commands here.
        // Example: \App\Console\Commands\YourCustomCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Define scheduled commands here.
        // Example: $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // This method loads the commands from your application.
        // If you have commands in the 'app/Console/Commands' directory, they will be automatically loaded.
        $this->load(__DIR__.'/Commands');

        // Load the console routes from 'routes/console.php'
        require base_path('routes/console.php');
    }
}
