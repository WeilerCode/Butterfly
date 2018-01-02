<?php

namespace Weiler\Butterfly\Http\Controllers\Admin\Manage;

use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Models\User;
use Weiler\Butterfly\Models\UserAdminGroup;

class MemberController extends AdminController
{
    public function index(Request $request)
    {
        // 获取当前用户权限范围内的后台用户
        $members = User::where('type', 'system')
            ->where('lv', $request->user()->id === 1 ? '>=' : '>', $request->user()->lv)
            ->orderBy('id', 'desc')
            ->paginate(20);
        $group = UserAdminGroup::all()->keyBy('id');
        return view('butterfly::admin.manage.member')->with(['members' => $members, 'group' => $group]);
    }

    public function getAdd()
    {
        return view('butterfly::admin.manage.member-add');
    }
    public function postAdd(Request $request)
    {}

    public function getEdit($id)
    {}
    public function postEdit($id, Request $request)
    {}

    public function getDel($id)
    {}
}