<?php

namespace Weiler\Butterfly\Http\Controllers\Admin\Manage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Weiler\Butterfly\Http\Controllers\AdminController;

class LogController extends AdminController
{
    public function index(Request $request)
    {
        // 获取日志列表
        $log = DB::table('butterfly_admin_log')
            ->leftJoin('butterfly_users', 'butterfly_admin_log.uid', '=', 'butterfly_users.id')
            ->select('butterfly_admin_log.*', 'butterfly_users.realName', 'butterfly_users.name')
            ->orderBy('butterfly_admin_log.created_at', 'desc')
            ->paginate(10);
        return view('butterfly::admin.manage.log')->with(['log' => $log]);
    }
}