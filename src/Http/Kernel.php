<?php

namespace Weiler\Butterfly\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'butterfly.admin.guest' => \Weiler\Butterfly\Http\Middleware\AdminAuthGuest::class
    ];
}
