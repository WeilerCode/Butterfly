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
        echo "This is test";
    }

    public function register()
    {

    }
}