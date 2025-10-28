<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CreateTestDatabase::class,
        \App\Console\Commands\TestSetup::class,
    ];

    protected $schedule = [
        \App\Console\Commands\TestSetup::class => [
            'command' => 'test:setup',
            'description' => 'Setup test data',
            'frequency' => 'daily',
            'timezone' => 'UTC',
        ],
    ];

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // ...existing commands...
        \App\Console\Commands\CreateTestDatabase::class,
        \App\Console\Commands\TestSetup::class,
    ];

    /**
     * Register a custom command.
     *
     * @param  string  $name
     * @param  \Closure  $callback
     */
    public function command($name, $callback)
    {
        $this->commands[] = $callback;
    }

    /**
     * Run the application.
     */
    public function run()
    {
        $this->schedule->run();
    }
}
