<?php

namespace Weiler\Butterfly\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;

class MeController extends AdminController
{
    public function index()
    {
        return view('butterfly::admin.me');
    }

    public function update(Request $request)
    {

    }
}