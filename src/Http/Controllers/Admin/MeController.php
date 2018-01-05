<?php

namespace Weiler\Butterfly\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Models\User;
use Weiler\UploadImg\UploadImg;

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

        //修改前的值
        $origin = User::find($request->user()->id)->toArray();
        // update
        if (User::where('id', $request->user()->id)->update($request->input('password') ?
            $request->except('_token', 'password_confirmation') :
            $request->except('_token', 'password', 'password_confirmation')
        )) {
            // setLog
            $this->setLog($request->user()->id, 'update', 'me.update', json_encode($origin), json_encode($request->except('_token', 'password_confirmation')));
            return butterflyAdminJump('success', getLang('Tips.updateSuccess'), '', 1);
        }
        return butterflyAdminJump('error', getLang('Tips.updateFail'), '', 1);
    }

    /**
     * 上传图片
     * @param UploadImg $uploadImg
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImg(UploadImg $uploadImg, Request $request)
    {
        if ($request->hasFile('cropperFile'))
        {
            if ($request->file('cropperFile')->isValid())
            {
                $avatar_file = $request->file('cropperFile');
                $avatar_data = $request->input('cropperData');
                $avatar_data_obj = json_decode(stripslashes($avatar_data));
                $uploadImg->savePath = config('butterfly.upload.member_path').$request->user()->id.'/';
                //横纵比例
                $prefix = md5(time());
                $aspectRatio = [
                    [
                        'width'     =>  $avatar_data_obj->width,
                        'height'    =>  $avatar_data_obj->height,
                        'path'      =>  $prefix.'.png'
                    ]
                ];
                $backData = $uploadImg->upThumb($avatar_file, $avatar_data, $aspectRatio);
                if(count($backData) > 0)
                {
                    $origin = User::where('id', $request->user()->id)->first();
                    //更新数据
                    $check = User::where('id', $request->user()->id)->update(['thumb' => $backData['data']['name']]);
                    if($check)
                    {
                        // setLog
                        $this->setLog($request->user()->id, 'update', 'me.uploadImg', json_encode($origin), json_encode($backData));
                        $backData['msg'] = '更新成功';
                    }else{
                        $backData['msg'] = '更新失败';
                    }
                    $backData['data']['path'] = asset($backData['data']['path']);
                    //返回数据
                    return response()->json($backData);
                }
            }
        }
    }
}