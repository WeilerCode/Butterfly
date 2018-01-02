<?php

namespace Weiler\Butterfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}