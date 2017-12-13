<?php

namespace Weiler\Butterfly\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
                //将分组列表，权限目录，当前用户加入到
                $request->adminGroup = $this->adminGroup;
                $request->groupMenu = $this->menu;
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
            //获取并验证分组权限目录
            if ($this->groupMenu()) {
                //获取当前routeName
                $routeName = $request->route()->getName();
                //检测是否有权限访问当前目录
                $verify = array_where($this->menu, function($v, $k) use($routeName)
                {
                    if ($v['routeName'] == $routeName)
                    {
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
                //获取当前用户的分组信息
                $group = isset($this->adminGroup[$this->user->groupID]) ? $this->adminGroup[$this->user->groupID] : null;
                if (!$group)
                    return false;

                //获取有权限的目录
                $permissions = explode(',', $group->permissions);
                $this->menu = AdminMenu::whereIn('id', $permissions)->orderBy('listOrder', 'ASC')->get()->keyBy('id')->toArray();
            } else {
                return false;
            }
        }
        return true;
    }
}
