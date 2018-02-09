<?php

namespace Weiler\Butterfly\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Models\AdminLog;
use Weiler\Butterfly\Models\User;

class IndexController extends AdminController
{
    public function index(Request $request)
    {
        // 硬盘使用率
        $dt = round(@disk_total_space(".") / (1024*1024*1024),3); //总
        $df = round(@disk_free_space(".") / (1024*1024*1024),3);  //可用
        $du = $dt-$df;  //已用
        $hdPercent = (floatval($dt)!=0) ? round($du/$dt*100,2) : 0;
        // 会员总数
        $memberNum = User::where('type', 'member')->count();
        // 已验证会员数
        $verifyMemberNum = User::where('type', 'member')->where('verify', 1)->count();
        // 后台日志总数
        $logNum = AdminLog::count();
        return view('butterfly::admin.index')->with(['memberNum' => $memberNum, 'verifyMemberNum' => $verifyMemberNum, 'logNum' => $logNum, 'hdPercent' => $hdPercent]);
    }
}