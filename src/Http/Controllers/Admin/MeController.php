<?php

namespace Weiler\Butterfly\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Models\User;

class MeController extends AdminController
{
    public function index()
    {
        return view('butterfly::admin.me');
    }

    /**
     * 更新我的信息
     * @param Request $request
     * @return $this|string
     */
    public function update(Request $request)
    {
        // 验证条件
        $rule = [
            'realName'      =>  'max:255',
            'email'         =>  'email|max:255|unique:butterfly_users,email,'.$request->user()->id,
            'phone'         =>  'unique:butterfly_users,phone,'.$request->user()->id
        ];
        if ($request->input('password'))
            $rule['password'] = 'confirmed|min:6';
        // 表单验证
        $validator = $this->validator($request->input(), $rule);
        if ($validator)
            return redirect()->back()->withErrors($validator)->withInput();

        // update
        if (User::where('id', $request->user()->id)->update($request->input('password') ?
            $request->except('_token', 'password_confirmation') :
            $request->except('_token', 'password', 'password_confirmation')
        )) {
            return butterflyAdminJump('success', __('butterfly::Tips.updateSuccess'), '', 1);
        }
        return butterflyAdminJump('error', __('butterfly::Tips.updateFail'), '', 1);
    }
}