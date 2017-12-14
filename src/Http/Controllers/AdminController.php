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
}