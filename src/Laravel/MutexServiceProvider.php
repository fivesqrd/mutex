<?php

namespace Fivesqrd\Mutex\Laravel;

use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Support;
use Aws\DynamoDb;
use Fivesqrd\Mutex;

/**
 * Atlas service provider
 */
class MutexServiceProvider extends Support\ServiceProvider
{
    
    /**
     * Bootstrap the configuration
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/Config.php' => config_path('mutex.php')]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mutex', function ($app) {
            return new Mutex\Factory(
                $app->make('config')->get('mutex')
            );
        });

        $this->commands([
            Console\Test::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['mutex'];
    }
}
