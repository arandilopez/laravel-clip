<?php

namespace Clip;

use Illuminate\Support\ServiceProvider;
use Clip\Clip;

class ClipServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Clip::class, function () {
            return new Clip();
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->publishes([
            __DIR__.'/config/clip.php' => config_path('clip.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/config/clip.php', 'clip'
        );
    }
}
