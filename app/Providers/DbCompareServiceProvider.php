<?php

namespace App\Providers;

use App\DbCompare\DbCompareManager;
use Illuminate\Support\ServiceProvider;

class DbCompareServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

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
        $this->app->singleton('App\Contracts\DbCompareFactory', function ($app) {
            return new DbCompareManager($app);
        });
    }

    public function provides()
    {
        return ['App\Contracts\DbCompareFactory'];
    }
}
