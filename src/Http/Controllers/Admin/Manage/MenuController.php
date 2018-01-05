<?php

namespace Weiler\Butterfly\Http\Controllers\Admin\Manage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Jobs\RecordLog;
use Weiler\Butterfly\Models\AdminMenu;
use Weiler\EasyTree\EasyTree;

class MenuController extends AdminController
{
    /**
     * 目录管理
     * @return $this
     */
    public function index()
    {
        $menu = AdminMenu::all()->toArray();
        $tree = Cache::remember('butterfly.cache_name.admin_menu', 1440, function() use ($menu) {

            foreach ($menu as $k=>$v)
            {
                $menu[$k]['cname']        =   getLang($v['name']);
                $menu[$k]['checked']      =   $v['display'] == 1 ? 'checked' : 'checked-false';
                $menu[$k]['addChildUrl']  =   route('admin-manage-menu-add', ['parentID' => $v['id']]);
                $menu[$k]['editUrl']      =   route('admin-manage-menu-edit', ['id' => $v['id']]);
                $menu[$k]['deleteUrl']    =   route('admin-manage-menu-del', ['id' => $v['id']]);
            }
            //设置模板
            $template = "<tr>
                            <td class='text-center border-right'><input type='text' name='listorder[\$v[id]]' value='\$v[listOrder]' style='width:40px;'></td>
                            <td class='text-center'><i class='\$v[icon]'></i></td>
                            <td>\$spacer \$v[cname]</td>
                            <td class='text-center'><input class='switch switch-mini' type='checkbox' data-id='\$v[id]' name='show[\$v[id]]' \$v[checked] data-on-text='".getLang('Tips.showON')."' data-off-text='".getLang('Tips.showOFF')."' data-label-width='0' data-on-color='success' data-off-color='danger' data-size='mini'></td>
                            <td>\$v[routeName]</td>
                            <td class='text-center'>
                                <a href='\$v[addChildUrl]' class='btn btn-success btn-xs' data-toggle='tooltip' data-placement='top' data-original-title='".getLang('Tips.createSonMenu')."'><i class='fa fa-plus'></i></a>
                                <a href='\$v[editUrl]' class='btn btn-primary btn-xs' data-toggle='tooltip' data-placement='top' data-original-title='".getLang('Tips.edit')."'><i class='fa fa-pencil'></i></a>
                                <button type='button' data-url='\$v[deleteUrl]' data-tips='".getLang('Tips.isDelete')." : \$v[cname]' class='btn btn-danger btn-xs delete-table' data-toggle='tooltip' data-placement='top' data-original-title='".getLang('Tips.delete')."'><i class='fa fa-times'></i></button>
                            </td>
                     </tr>";
            //创建目录树HTML
            $tree = new EasyTree($menu);
            return $tree->setTemplate($template)->getTree();
        });
        return view('butterfly::admin.manage.menu')->with(['tree' => $tree]);
    }

    /**
     * 添加目录
     * @param int $parentID
     * @return $this
     */
    public function getAdd($parentID = 0)
    {
        //获取栏目列表
        $tree = $this->getSelect($parentID);

        return view('butterfly::admin.manage.menu-add')->with(['tree' => $tree]);
    }
    public function postAdd(Request $request)
    {
        // 验证条件
        $rule = [
            'parentID'      =>  'required',
            'name'          =>  'required',
            'listOrder'     =>  'required'
        ];
        if ($request->input('routeName'))
            $rule['routeName'] = 'unique:butterfly_admin_menu';
        // 表单验证
        $validator = $this->validator($request->input(), $rule);
        if ($validator)
            return redirect()->back()->withErrors($validator)->withInput();
        $data = [
            'name'      =>  $request->input('name'),
            'parentID'  =>  $request->input('parentID'),
            'routeName' =>  $request->input('routeName') ? $request->input('routeName') : '',
            'listOrder' =>  $request->input('listOrder'),
            'display'   =>  $request->has('display') ? 1 : 0,
            'icon'      =>  $request->input('icon') ? $request->input('icon') : ''
        ];
        if (AdminMenu::create($data)) {
            // 清除目录树缓存
            Cache::forget('butterfly.cache_name.admin_menu');
            // setLog
            $this->setLog($request->user()->id, 'create', 'adminLogEvent.manage.menu.add', NULL, json_encode($data));
            return butterflyAdminJump('success', getLang('Tips.createSuccess'), route('admin-manage-menu'), 1);
        }
        return butterflyAdminJump('error', getLang('Tips.createFail'), route('admin-manage-menu'), 1);
    }

    /**
     * 修改目录
     * @param $id
     * @return $this
     */
    public function getEdit($id)
    {
        // 获取目录信息
        $thisMenu = AdminMenu::find($id);
        // 获取栏目列表
        $tree = $this->getSelect($thisMenu->parentID);

        return view('butterfly::admin.manage.menu-edit')->with(['tree' => $tree, 'thisMenu' => $thisMenu]);
    }
    public function postEdit($id, Request $request)
    {
        // 验证条件
        $rule = [
            'parentID'      =>  'required',
            'name'          =>  'required',
            'listOrder'     =>  'required'
        ];
        if ($request->input('routeName'))
            $rule['routeName'] = 'unique:butterfly_admin_menu,routeName,'.$id;
        // 表单验证
        $validator = $this->validator($request->input(), $rule);
        if ($validator)
            return redirect()->back()->withErrors($validator)->withInput();

        $data = [
            'name'      =>  $request->input('name'),
            'parentID'  =>  $request->input('parentID'),
            'routeName' =>  $request->input('routeName') ? $request->input('routeName') : '',
            'listOrder' =>  $request->input('listOrder'),
            'display'   =>  $request->has('display') ? 1 : 0,
            'icon'      =>  $request->input('icon') ? $request->input('icon') : ''
        ];
        // 修改前的值
        $origin = AdminMenu::find($id)->toArray();
        if (AdminMenu::where('id', $id)->update($data)) {
            // 清除目录树缓存
            Cache::forget('butterfly.cache_name.admin_menu');
            // setLog
            $this->setLog($request->user()->id, 'update', 'adminLogEvent.manage.menu.edit', json_encode($origin), json_encode($data));
            return butterflyAdminJump('success', getLang('Tips.updateSuccess'), route('admin-manage-menu-edit', ['id' => $id]), 1);
        }
        return butterflyAdminJump('error', getLang('Tips.updateFail'), route('admin-manage-menu-edit', ['id' => $id]), 1);
    }

    /**
     * 删除目录
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDel($id, Request $request)
    {
        //获取目录
        $menu = AdminMenu::all()->keyBy('id')->toArray();
        //获得自身和子目录集合
        $child = new EasyTree($menu);
        $child = $child->getContinue($id);
        $array = [(int)$id];
        if ($child)
        {
            $array = array_merge($array, $child);
        }

        //获取原值
        $origin = AdminMenu::whereIn('id', $array)->get()->toArray();

        if (AdminMenu::destroy($array))
        {
            // 清除目录树缓存
            Cache::forget('butterfly.cache_name.admin_menu');
            // setLog
            $this->setLog($request->user()->id, 'del', 'adminLogEvent.manage.menu.del', json_encode($origin), NULL);
            return butterflyAdminJump('success', getLang('Tips.deleteSuccess'),'',1);
        }
        return butterflyAdminJump('error', getLang('Tips.illegal'),'',1);
    }

    /**
     * Ajax是否显示
     * @param Request $request
     * @return string
     */
    public function display(Request $request)
    {
        $menu = AdminMenu::find($request->input('id'));
        if(!empty($menu))
        {
            // 修改前
            $origin = $menu->toArray();
            $menu->display = $request->input('display');
            if ($menu->save())
            {
                // 清除目录树缓存
                Cache::forget('butterfly.cache_name.admin_menu');
                // setLog
                $this->setLog($request->user()->id, 'del', 'adminLogEvent.manage.menu.display', json_encode($origin), json_encode($request->except('_token')));
                return json_encode([
                    'result'    =>  'OK',
                    'code'      =>  200,
                    'msg'       =>  getLang('Tips.updateSuccess')
                ]);
            }
        }
        return json_encode([
            'result'    =>  'NO',
            'code'      =>  400,
            'msg'       =>  getLang('Tips.updateFail')
        ]);
    }

    /**
     * 获取添加和修改目录里的选项目录
     * @param $parentID
     * @return string
     */
    private function getSelect($parentID)
    {
        $menu = AdminMenu::all()->toArray();
        foreach ($menu as $k=>$v)
        {
            $menu[$k]['cname']    =   getLang($v['name']);
            $menu[$k]['selected'] = '';
            if ($parentID == $v['id'])
            {
                $menu[$k]['selected'] = 'selected';
            }
        }
        $template = "<option value='\$v[id]' \$v[selected]>\$spacer \$v[cname]</option>";
        $tree = new EasyTree($menu);

        return $tree->setTemplate($template)->getTree();
    }
}