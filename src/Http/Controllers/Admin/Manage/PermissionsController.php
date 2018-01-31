<?php

namespace Weiler\Butterfly\Http\Controllers\Admin\Manage;

use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Models\User;
use Weiler\Butterfly\Models\UserAdminGroup;
use Weiler\EasyTree\EasyTree;

class PermissionsController extends AdminController
{

    /**
     * 权限管理
     * @return $this
     */
    public function index()
    {
        // 获取分组列表
        $group = UserAdminGroup::all();
        return view('butterfly::admin.manage.permissions')->with(['group' => $group]);
    }

    /**
     * 添加分组
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAddGroup()
    {
        return view('butterfly::admin.manage.group-add');
    }
    public function postAddGroup(Request $request)
    {
        // 验证条件
        $rule = [
            'lv'            =>  'required|integer',
            'name'          =>  'required|unique:butterfly_admin_group'
        ];
        $rule['lv'] .= $request->user()->id !== 1 ? '|min:'.($request->user()->lv+1) : '|min:'.($request->user()->lv);
        // 表单验证
        $validator = $this->validator($request->input(), $rule);
        if ($validator)
            return redirect()->back()->withErrors($validator)->withInput();

        $data = [
            'name'          =>  $request->input('name'),
            'lv'            =>  $request->input('lv'),
            'color'         =>  $request->input('color'),
            'permissions'   =>  '1,2,3,4'
        ];
        if (UserAdminGroup::create($data)) {
            // setLog
            $this->setLog($request->user()->id, 'create', 'adminLogEvent.manage.permissions.add', NULL, json_encode($data));
            return butterflyAdminJump('success', getLang('Tips.createSuccess'), route('admin-manage-permissions'), 1);
        }
        return butterflyAdminJump('error', getLang('Tips.createFail'), route('admin-manage-permissions'), 1);
    }

    /**
     * 修改分组
     * @param $id
     * @return $this
     */
    public function getEditGroup($id)
    {
        //获取当前分组
        $group = UserAdminGroup::find($id);
        return view('butterfly::admin.manage.group-edit')->with(['group' => $group]);
    }
    public function postEditGroup($id, Request $request)
    {
        // 验证条件
        $rule = [
            'lv'            =>  'required|integer',
            'name'          =>  'required|unique:butterfly_admin_group,name,'.$id
        ];
        $rule['lv'] .= $request->user()->id !== 1 ? '|min:'.($request->user()->lv+1) : '|min:'.($request->user()->lv).'|size:1';
        // 表单验证
        $validator = $this->validator($request->input(), $rule);
        if ($validator)
            return redirect()->back()->withErrors($validator)->withInput();

        // 修改前的值
        $origin = UserAdminGroup::find($id)->toArray();
        $data = [
            'name'          =>  $request->input('name'),
            'lv'            =>  $request->input('lv'),
            'color'         =>  $request->input('color')
        ];
        if (UserAdminGroup::where('id', $id)->update($data)) {
            // setLog
            $this->setLog($request->user()->id, 'update', 'adminLogEvent.manage.permissions.edit', json_encode($origin), json_encode($data));
            return butterflyAdminJump('success', getLang('Tips.updateSuccess'), route('admin-manage-permissions-edit-group', ['id' => $id]), 1);
        }
        return butterflyAdminJump('error', getLang('Tips.updateFail'), route('admin-manage-permissions-edit-group', ['id' => $id]), 1);
    }

    /**
     * 删除分组
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDelGroup($id, Request $request)
    {
        if ((int)$id === 1)
            return butterflyAdminJump('error', getLang('Tips.groupNotDelete'),'',1);
        // 获取用户
        $adminGroup = UserAdminGroup::find($id);
        // 删除前的数值
        $origin = $adminGroup->toArray();
        if (!empty($adminGroup))
        {
            if ($this->verifyIllegality($adminGroup->lv, $request))
                return butterflyAdminJump('error', getLang('Tips.illegal'), '', 1);
            if ($adminGroup->delete())
            {
                //删除分组下的用户
                User::where('type', 'system')->where('groupID',$id)->delete();
                // setLog
                $this->setLog($request->user()->id, 'delete', 'adminLogEvent.manage.permissions.del', json_encode($origin), NULL);
                return butterflyAdminJump('success', getLang('Tips.deleteSuccess'),'',1);
            }
        }
        return butterflyAdminJump('error', getLang('Tips.illegal'),'',1);
    }

    /**
     * 修改权限
     * @param $groupID
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPermissions($groupID, Request $request)
    {
        if ((int)$groupID === 1)
            return butterflyAdminJump('warning', getLang('Tips.groupNotConfig'),'',1);
        //整理目录
        $thisGroup = UserAdminGroup::find($groupID);
        if ($this->verifyIllegality($thisGroup->lv, $request))
            return butterflyAdminJump('error', getLang('Tips.illegal'), '', 1);
        $permissions = explode(',',$thisGroup->permissions);
        $menu = $request->get('menus');
        foreach ($menu as $k=>$v)
        {
            $menu[$k]['level']        =   $this->getLevel($v['id'], $menu);
            $menu[$k]['checked']      =   in_array($v['id'], $permissions) ? ' checked' : '';
            $menu[$k]['pesName']      =   $v['parentID'] == 0 ? '<b>'.getLang($v['name']).'</b>' : getLang($v['name']);
        }

        $template = "<tr>
                         <td class='text-center border-right'><input type='checkbox' name='checked[]' value='\$v[id]' level='\$v[level]' \$v[checked]/></td>
                         <td class='text-center border-right'><i class='\$v[icon]'></i></td>
                         <td>\$spacer \$v[pesName]</td>
                     </tr>";

        //创建目录树HTML
        $tree = new EasyTree($menu);
        $tree = $tree->setTemplate($template)->getTree();
        return view('butterfly::admin.manage.permissions-manage')->with(['tree' => $tree, 'groupID' => $groupID]);
    }
    public function postPermissions($groupID, Request $request)
    {
        $thisGroup = UserAdminGroup::find($groupID);
        // 修改前
        $origin = $thisGroup->toArray();
        //验证是否越级
        if ($this->verifyIllegality($thisGroup->lv, $request))
            return butterflyAdminJump('error', getLang('Tips.illegal'));
        //验证提交的菜单是否有权限修改
        if ($request->user()->groupID != 1) {
            $userGroup = UserAdminGroup::find($request->user()->groupID);
            if (empty(array_diff(explode(',', $userGroup->permissions), $request->input('checked'))))
                return butterflyAdminJump('error', getLang('Tips.illegal'));
        }
        $thisGroup->permissions = $request->has('checked') ? implode(',', $request->input('checked')) : '1';
        if ($thisGroup->save()) {
            // setLog
            $this->setLog($request->user()->id, 'update', 'adminLogEvent.manage.permissions.permissions', json_encode($origin), json_encode($thisGroup->permissions));
            return butterflyAdminJump('success', getLang('Tips.updateSuccess'),'',1);
        }
        return butterflyAdminJump('error', getLang('Tips.updateFail'),'',1);
    }

    /**
     * 获取菜单深度
     * @param $id
     * @param $menu
     * @param int $i
     * @return int
     */
    private function getLevel($id, $menu, $i=0) {
        foreach($menu as $v){
            if($v['id'] == $id)
            {
                if($v['parentID'] == '0') return $i;
                $i++;
                return $this->getLevel($v['parentID'], $menu, $i);
            }
        }
    }
}