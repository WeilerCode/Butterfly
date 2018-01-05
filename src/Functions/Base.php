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
 * @param $errors \Illuminate\Support\Facades\Validator 表单验证信息对象
 * @param $name string 当前验证字段
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

/** Json数据格式化
 * @param  array  $data   数据
 * @param  String $indent 缩进字符，默认4个空格
 * @return string
 */
function jsonFormat($data, $indent=null){
    // json encode
    $data = json_encode($data,JSON_UNESCAPED_UNICODE);

    // 缩进处理
    $ret = '';
    $pos = 0;
    $length = strlen($data);
    $indent = isset($indent)? $indent : '    ';
    $newline = "\n";
    $prevchar = '';
    $outofquotes = true;

    for($i=0; $i<=$length; $i++){

        $char = substr($data, $i, 1);

        if($char=='"' && $prevchar!='\\'){
            $outofquotes = !$outofquotes;
        }elseif(($char=='}' || $char==']') && $outofquotes){
            $ret .= $newline;
            $pos --;
            for($j=0; $j<$pos; $j++){
                $ret .= $indent;
            }
        }

        $ret .= $char;

        if(($char==',' || $char=='{' || $char=='[') && $outofquotes){
            $ret .= $newline;
            if($char=='{' || $char=='['){
                $pos ++;
            }

            for($j=0; $j<$pos; $j++){
                $ret .= $indent;
            }
        }

        $prevchar = $char;
    }

    return $ret;
}

//获取用户真实IP
function getIp() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else
        if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else
            if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
                $ip = getenv("REMOTE_ADDR");
            else
                if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
                    $ip = $_SERVER['REMOTE_ADDR'];
                else
                    $ip = "unknown";
    return ($ip);
}