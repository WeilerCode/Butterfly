<?php

namespace Weiler\Butterfly\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Weiler\Butterfly\Models\AdminMenu;
use Weiler\Butterfly\Models\UserAdminGroup;

class AdminAuth
{
    protected $auth;
    protected $user;
    protected $adminGroup;
    protected $menu;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // TODO:后台用户权限
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(action('\Weiler\Butterfly\Http\Controllers\Admin\Auth\AuthController@showLoginForm'));
            }
        } else {
            $this->user = $request->user();
            $this->getAdminGroup();
            if ($this->verify($request))
            {
                // 设置后台视图的局部变量
                $this->setViewShare();
                // 添加权限目录到request
                $request->attributes->add(['menus' => $this->menu]);
                return $next($request);
            }else{
                //return ShowMsg('error','你没有权限访问此页面！','');
                return response('Unauthorized.', 404);
            }
        }
    }

    /**
     * 获取后台用户分组并加入到缓存中
     */
    private function getAdminGroup()
    {
        $this->adminGroup = Cache::remember(config('butterfly.cache_name.admin_group'), 1440, function () {
            return UserAdminGroup::all()->keyBy('id')->toArray();
        });
    }

    /**
     * 验证用户权限
     * @param Request $request
     * @return bool
     */
    private function verify(Request $request)
    {
        if ($this->user && $this->user->type === 'system') {
            // 获取并验证分组权限目录
            if ($this->groupMenu()) {
                // 获取当前routeName
                $routeName = $request->route()->getName();
                // 检测是否有权限访问当前目录
                $verify = array_where($this->menu, function($v, $k) use($routeName)
                {
                    if ($v['routeName'] == $routeName)
                    {
                        $this->menu[$v['id']]['current'] = 1;   //当前action标记
                        $this->getActive($v['id']);
                        return true;
                    }
                    return false;
                });
                if ($verify)
                    return true;
            }
        }
        return false;
    }

    /**
     * 获取并验证分组权限目录
     * @return bool
     */
    private function groupMenu()
    {
        if ($this->user->groupID === 1) {
            $this->menu = AdminMenu::orderBy('listOrder', 'ASC')->get()->keyBy('id')->toArray();
        } else {
            if ($this->adminGroup) {
                // 获取当前用户的分组信息
                $group = isset($this->adminGroup[$this->user->groupID]) ? $this->adminGroup[$this->user->groupID] : null;
                if (!$group)
                    return false;

                // 获取有权限的目录
                $permissions = explode(',', $group->permissions);
                $this->menu = AdminMenu::whereIn('id', $permissions)->orderBy('listOrder', 'ASC')->get()->keyBy('id')->toArray();
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取Active相应目录
     * @param $id
     * @param $menu
     */
    private function getActive($id)
    {
        // 当前目录加入Active
        $this->menu[$id]['active'] = 1;
        // 上级目录加入Active
        if($this->menu[$id]['parentID'] != 0)
        {
            $this->menu[$this->menu[$id]['parentID']]['active'] = 1;
            $this->getActive($this->menu[$id]['parentID']);
        }
    }

    /**
     * 设置后台视图的局部变量
     */
    private function setViewShare()
    {
        // 面包屑导航
        $B_MENU = array_where($this->menu, function($v, $k) {
            return isset($v['active']) && $v['active'] == 1;
        });
        $B_MENU = array_values(array_sort($B_MENU, function ($v) {
            return $v;
        }));

        // 一级导航
        $F_MENU = [];
        foreach ($this->menu as $v) {
            if ($v['parentID'] == 0) {
                $v['url'] = empty($v['routeName']) ? '' : route($v['routeName']);
                array_push($F_MENU, $v);
            }
        }

        // 二级导航
        $S_MENU = [];
        foreach ($F_MENU as $v) {
            $S_MENU[$v['id']] = [];
            foreach ($this->menu as $m) {
                if ($m['parentID'] == $v['id'] && $m['display'] == 1) {
                    $m['url'] = empty($m['routeName']) ? '' : route($m['routeName']);
                    array_push($S_MENU[$v['id']], $m);
                }
            }
        }

        View::share(['B_MENU' => $B_MENU, 'F_MENU' => $F_MENU, 'S_MENU' => $S_MENU, 'USER' => $this->user, 'A_GROUP' => $this->adminGroup]);
    }
}
