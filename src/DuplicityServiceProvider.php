<?php

namespace Outlawplz\Duplicity;

use Illuminate\Support\ServiceProvider;
use Outlawplz\Duplicity\Commands\DuplicityBackup;
use Outlawplz\Duplicity\Commands\DuplicityRestore;

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

        $this->app->bind(Duplicity::class, function () {
            return new Duplicity(base_path());
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
                DuplicityBackup::class,
                DuplicityRestore::class,
            ]);
        }
    }
}
