<?php

namespace App\Aura\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AuraServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('aura', function() {
            return new \App\Aura\Aura(config('aura'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
