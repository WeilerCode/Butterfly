<?php
/**
 * Created by Weiler.
 * User: Weiler
 * Email: weiler.china@gmail.com
 * Date: 2017/11/13
 * Time: 15:50
 */

namespace Weiler\Butterfly\Providers;

use Illuminate\Support\ServiceProvider;
use Weiler\Butterfly\Commands\Init;

class ButterflyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        include_once __DIR__.'/../Functions/Base.php';
        // Routes
        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        // Views & Translations
        $this->loadViewsFrom(__DIR__.'/../Views', 'butterfly');
        $this->loadTranslationsFrom(__DIR__.'/../Lang/Butterfly', 'butterfly');
        // migrations
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        // publish
        $this->publishes([
            // config
            __DIR__.'/../Config/butterfly.php' => config_path('butterfly.php'),
            // Assets
            __DIR__.'/../Assets' => public_path('vendor/butterfly'),
            // View
            __DIR__.'/../Views' => resource_path('views/vendor/butterfly'),
            // laravel translation
            __DIR__.'/../Lang/Laravel' => resource_path('lang'),
            // butterfly translation
            __DIR__.'/../Lang/Butterfly' => resource_path('lang/vendor/butterfly'),
        ], 'butterfly');

        // commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Init::class
            ]);
        }
    }

    public function register()
    {
        $this->app->singleton(
            \Illuminate\Contracts\Http\Kernel::class,
            \Weiler\Butterfly\Http\Kernel::class
        );
        $this->app['config']['auth.providers'] = config('butterfly.providers');
    }
}