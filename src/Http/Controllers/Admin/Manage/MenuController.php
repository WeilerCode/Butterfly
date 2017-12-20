<?php

namespace Weiler\Butterfly\Http\Controllers\Admin\Manage;

use Weiler\Butterfly\Http\Controllers\AdminController;

class MenuController extends AdminController
{
    public function index()
    {
        return view('butterfly::admin.manage.menu');
    }


}