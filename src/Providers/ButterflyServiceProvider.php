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
            __DIR__.'/Config/butterfly.php' => config_path('butterfly.php'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    public function register()
    {

    }
}