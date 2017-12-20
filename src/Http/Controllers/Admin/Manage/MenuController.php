<?php

namespace Weiler\Butterfly\Http\Controllers\Admin\Manage;

use Illuminate\Support\Facades\Cache;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Models\AdminMenu;
use Weiler\EasyTree\EasyTree;

class MenuController extends AdminController
{
    public function index()
    {
        $menu = AdminMenu::all()->toArray();
        $tree = Cache::remember('butterfly.cache_name.admin_menu', 1440, function() use ($menu) {

            foreach ($menu as $k=>$v)
            {
                $menu[$k]['cname']        =   __('butterfly::'.$v['name']);
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
                            <td class='text-center'><input class='switch switch-mini' type='checkbox' data-id='\$v[id]' name='show[\$v[id]]' \$v[checked] data-on-text='".__('butterfly::Tips.showON')."' data-off-text='".__('butterfly::Tips.showOFF')."' data-label-width='0' data-on-color='success' data-off-color='danger' data-size='mini'></td>
                            <td>\$v[routeName]</td>
                            <td class='text-center'>
                                <a href='\$v[addChildUrl]' class='btn btn-success btn-xs' data-toggle='tooltip' data-placement='top' data-original-title='".__('butterfly::Tips.createSonMenu')."'><i class='fa fa-plus'></i></a>
                                <a href='\$v[editUrl]' class='btn btn-primary btn-xs' data-toggle='tooltip' data-placement='top' data-original-title='".__('butterfly::Tips.edit')."'><i class='fa fa-pencil'></i></a>
                                <button type='button' data-url='\$v[deleteUrl]' data-tips='".__('butterfly::Tips.isDelete')." : \$v[cname]' class='btn btn-danger btn-xs delete-table' data-toggle='tooltip' data-placement='top' data-original-title='".__('butterfly::Tips.delete')."'><i class='fa fa-times'></i></button>
                            </td>
                     </tr>";
            //创建目录树HTML
            $tree = new EasyTree($menu);
            return $tree->setTemplate($template)->getTree();
        });
        return view('butterfly::admin.manage.menu')->with(['tree' => $tree]);
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