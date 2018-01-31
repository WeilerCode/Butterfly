<?php

namespace Weiler\Butterfly\Http\Controllers\Admin\Member;

use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Models\UserMemberGroup;

class GroupController extends AdminController
{

    /**
     * 会员分组
     * @return $this
     */
    public function index()
    {
        // 获取分组列表
        $group = UserMemberGroup::orderBy('lv', 'asc')->get();
        return view('butterfly::admin.manage.permissions')->with(['group' => $group]);
    }

    public function getAdd()
    {}
    public function postAdd()
    {}

    public function getEdit()
    {}
    public function postEdit()
    {}

    public function getDel()
    {}
}