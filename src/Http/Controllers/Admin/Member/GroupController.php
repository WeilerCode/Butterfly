<?php

namespace Weiler\Butterfly\Http\Controllers\Admin\Member;

use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Models\User;
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

    /**
     * 添加分组
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

        $data = [
            'name'          =>  $request->input('name'),
            'lv'            =>  $request->input('lv'),
            'color'         =>  $request->input('color')
        ];
        if (UserMemberGroup::create($data)) {
            // setLog
            $this->setLog($request->user()->id, 'create', 'adminLogEvent.member.group.add', NULL, json_encode($data));
            return butterflyAdminJump('success', getLang('Tips.createSuccess'), route('admin-member-group'), 1);
        }
        return butterflyAdminJump('error', getLang('Tips.createFail'), route('admin-member-group'), 1);
    }

    /**
     * 修改分组
     * @param $id
     * @return $this
     */
    public function getEdit($id)
    {
        // 获取分组内容
        $group = UserMemberGroup::find($id);
        return view('butterfly::admin.member.group-edit')->with(['group' => $group]);
    }
    public function postEdit($id, Request $request)
    {
        // 验证条件
        $rule = [
            'lv'            =>  'required|integer',
            'name'          =>  'required|unique:butterfly_member_group,name,'.$id
        ];
        // 表单验证
        $validator = $this->validator($request->input(), $rule);
        if ($validator)
            return redirect()->back()->withErrors($validator)->withInput();

        // 修改前的值
        $origin = UserMemberGroup::find($id)->toArray();
        $data = [
            'name'          =>  $request->input('name'),
            'lv'            =>  $request->input('lv'),
            'color'         =>  $request->input('color')
        ];
        if (UserMemberGroup::where('id', $id)->update($data)) {
            // setLog
            $this->setLog($request->user()->id, 'update', 'adminLogEvent.member.group.edit', json_encode($origin), json_encode($data));
            return butterflyAdminJump('success', getLang('Tips.updateSuccess'), route('admin-member-group-edit', ['id' => $id]), 1);
        }
        return butterflyAdminJump('error', getLang('Tips.updateFail'), route('admin-member-group-edit', ['id' => $id]), 1);
    }

    /**
     * 删除分组
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDel($id, Request $request)
    {
        // 获取分组
        $adminGroup = UserMemberGroup::find($id);
        // 删除前
        $origin = $adminGroup->toArray();
        if (!empty($adminGroup)) {
            if ($adminGroup->delete()) {
                //删除分组下的用户
                User::where('type', 'member')->where('groupID',$id)->delete();
                // setLog
                $this->setLog($request->user()->id, 'delete', 'adminLogEvent.member.group.del', json_encode($origin), NULL);
                return butterflyAdminJump('success', getLang('Tips.deleteSuccess'),'',1);
            }
        }
        return butterflyAdminJump('error', getLang('Tips.illegal'),'',1);
    }
}