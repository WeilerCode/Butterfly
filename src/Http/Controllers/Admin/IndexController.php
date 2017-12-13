<?php

namespace Weiler\Butterfly\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;

class IndexController extends AdminController
{
    public function index(Request $request)
    {
        return view('butterfly::admin.index');
    }
}