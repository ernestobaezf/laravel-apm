<?php
/**
 * @author Ernesto Baez
 */

namespace LaravelAPM;


use Illuminate\Support\ServiceProvider;

class LaravelAPMProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."laravelAPM.php" =>
                    config_path("laravelAPM.php"),
            ], 'config'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."laravelAPM.php", "laravel_apm"
        );
    }
}
