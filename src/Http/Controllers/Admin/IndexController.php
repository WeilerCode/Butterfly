<?php

namespace Weiler\Butterfly\Http\Controllers\Admin;

use Weiler\Butterfly\Http\Controllers\AdminController;

class IndexController extends AdminController
{
    public function index()
    {
        return view('butterfly::admin.index');
    }
}