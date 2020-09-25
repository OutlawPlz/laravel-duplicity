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
        $this->mergeConfigFrom(
            __DIR__ . '../../config/duplicity.php', 'duplicity'
        );

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
        $this->publishes([
            __DIR__.'/../../config/duplicity.php' => config_path('duplicity.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                DuplicityBackup::class
            ]);
        }
    }
}
