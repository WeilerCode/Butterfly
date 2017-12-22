<?php

/**
 * 获取本地化语言
 * @param $key
 * @param string $namespace 模块命名
 * @return mixed
 */
function getLang($key, $namespace = 'butterfly')
{
    return Lang::has($key) ? Lang::get($key) : (Lang::has($namespace.'::'.$key) ? Lang::get($namespace.'::'.$key) : $key);
}

/**
 * 根据模板返回表单验证的单条错误信息
 * @param $errors 表单验证信息对象
 * @param $name 当前验证字段
 * @return string
 */
function getValidationErrorForTemplate($errors, $name)
{
    return $errors->has($name) ? $errors->first($name, '<span class="help-block">:message</span>') : '';
}

/**
 * 后台页面跳转提示
 * @param string $type success(成功) / warning-(警告) / error-(错误)
 * @param string $msg 跳转描述语句
 * @param null $url 跳转地址
 * @param int $seconds 停留几秒跳转
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
function butterflyAdminJump($type='success', $msg='', $url=null, $seconds=3)
{
    if (empty($url))
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
    switch($type) {
        case 'success':
            $title = 'success';
            break;
        case 'warning':
            $title = 'warning';
            break;
        case 'error':
            $title = 'error';
            break;
        default:
            $title = 'success';
            break;
    }
    $jump = 'butterfly::admin.prompt.jump-'.$title;
    $title = getLang('Tips.'.$title);
    return view($jump)->with(['jumpMsg' => $msg,'jumpUrl' => $url,'jumpSeconds' => $seconds,'title' => $title]);
}