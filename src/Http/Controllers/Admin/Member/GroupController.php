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
        return view('butterfly::admin.member.group')->with(['group' => $group]);
    }

    public function getAdd()
    {
        return view('butterfly::admin.member.group-add');
    }
    public function postAdd(Request $request)
    {
        // 验证条件
        $rule = [
            'lv'            =>  'required|integer|min:0',
            'name'          =>  'required|unique:butterfly_member_group'
        ];
        // 表单验证
        $validator = $this->validator($request->input(), $rule);
        if ($validator)
            return redirect()->back()->withErrors($validator)->withInput();

        if (UserMemberGroup::create([
            'name'          =>  $request->input('name'),
            'lv'            =>  $request->input('lv'),
            'color'         =>  $request->input('color')
        ])) {
            return butterflyAdminJump('success', getLang('Tips.createSuccess'), route('admin-member-group'), 1);
        }
        return butterflyAdminJump('error', getLang('Tips.createFail'), route('admin-member-group'), 1);
    }

    public function getEdit()
    {}
    public function postEdit()
    {}

    public function getDel()
    {}
}