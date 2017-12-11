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

class ButterflyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Config/butterfly.php' => config_path('butterfly.php'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->publishes([
            __DIR__.'/../Assets' => public_path('vendor/butterfly'),
        ], 'public');
        $this->loadViewsFrom(__DIR__.'/../Views', 'butterfly');
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