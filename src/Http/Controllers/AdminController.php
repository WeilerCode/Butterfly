<?php

namespace Weiler\Butterfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Weiler\Butterfly\Jobs\RecordLog;

class AdminController extends Controller
{
    /**
     * 表单验证
     * @param $input
     * @param array $rule
     * @return bool
     */
    protected function validator($input, $rule = [])
    {
        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            return $validator->errors();
        }
        return false;
    }
    /**
     * 验证是否越级操作
     * @param $lv
     * @param Request $request
     * @return bool
     */
    protected function verifyIllegality($lv, Request $request)
    {
        if ($request->user()->id != 1 && $request->user()->lv >= $lv)
            return true;
        return false;
    }

    /**
     * 记录后台日志
     * @param int $uid 执行事件的用户ID
     * @param $type (login/create/update/delete/other) 事件类型
     * @param string $event 事件名称
     * @param string $origin 事件发生前的数据
     * @param string $ending 事件发生后的数据
     */
    protected function setLog($uid, $type, $event = '', $origin = NULL, $ending = NULL)
    {
        $address = [
            'ip'        =>  getIP(),
            'iso_code'  =>  '',
            'city'      =>  ''
        ];
        RecordLog::dispatch($uid, $address, $event, time(), $type, $origin, $ending);
    }
}