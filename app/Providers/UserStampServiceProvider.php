<?php

namespace App\Providers;

use App\Support\UserStamp;
use Illuminate\Support\ServiceProvider;

class UserStampServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/userstamp.php',
            'userstamp'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/userstamp.php' => config_path('userstamp.php'),
        ], 'userstamp-config');

        // Set up custom user resolver if needed
        // UserStamp::resolveUsing(function() {
        //     return optional(auth()->user())->id;
        // });
    }
}
