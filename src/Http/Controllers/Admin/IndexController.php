<?php

namespace Weiler\Butterfly\Http\Controllers\Admin;

use Weiler\Butterfly\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('butterfly::admin.index');
    }
}