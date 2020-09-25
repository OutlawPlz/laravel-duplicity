<?php

namespace Outlawplz\Duplicity;

use Illuminate\Support\ServiceProvider;
use Outlawplz\Duplicity\Commands\DuplicityBackup;

class DuplicityServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('duplicity', function () {
            return new Duplicity();
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DuplicityBackup::class
            ]);
        }
    }
}
