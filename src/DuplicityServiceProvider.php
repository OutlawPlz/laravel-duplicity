<?php

namespace Outlawplz\Duplicity;

use Illuminate\Support\ServiceProvider;

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
}
