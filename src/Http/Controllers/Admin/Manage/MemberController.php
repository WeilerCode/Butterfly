<?php

namespace Weiler\Butterfly\Http\Controllers\Admin\Manage;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Models\User;
use Weiler\Butterfly\Models\UserAdminGroup;
use Weiler\UploadImg\UploadImg;

class MemberController extends AdminController
{
    public function index(Request $request)
    {
        // 获取当前用户权限范围内的后台用户
        $members = User::where('type', 'system')
            ->where('lv', $request->user()->id === 1 ? '>=' : '>', $request->user()->lv)
            ->orderBy('id', 'desc')
            ->paginate(20);
        $group = UserAdminGroup::all()->keyBy('id');
        return view('butterfly::admin.manage.member')->with(['members' => $members, 'group' => $group]);
    }

    /**
     * 添加分组
     * @return $this
     */
    public function getAdd()
    {
        // 获取分组
        $group = UserAdminGroup::all()->keyBy('id');
        return view('butterfly::admin.manage.member-add')->with(['group' => $group]);
    }
    public function postAdd(Request $request)
    {
        // 验证条件
        $rule = [
            'groupID'       =>  'required',
            'name'          =>  'required|unique:butterfly_users',
            'password'      =>  'required|confirmed|min:6'
        ];
        if ($request->input('email'))
            $rule['email'] = 'email|max:255|unique:butterfly_users';
        // 表单验证
        $validator = $this->validator($request->input(), $rule);
        if ($validator)
            return redirect()->back()->withErrors($validator)->withInput();
        // 获取group
        $group = UserAdminGroup::all()->keyBy('id');
        if (isset($group[$request->input('groupID')]) && User::create([
            'type'      =>  "system",
            'lv'        =>  $group[$request->input('groupID')]->lv,
            'name'      =>  $request->input('name'),
            'realName'  =>  $request->input('realName') ? $request->input('realName') : '',
            'thumb'     =>  '',
            'email'     =>  $request->input('email') ? $request->input('email') : '',
            'phone'     =>  $request->input('phone') ? $request->input('phone') : '',
            'verify'    =>  1,
            'verifyTime'=>  Carbon::create(),
            'groupID'   =>  $request->input('groupID'),
            'password'  =>  bcrypt($request->input('password'))
        ])) {
            return butterflyAdminJump('success', getLang('Tips.createSuccess'), route('admin-manage-member'), 1);
        }
        return butterflyAdminJump('error', getLang('Tips.createFail'), route('admin-manage-member'), 1);
    }

    /**
     * 修改后台用户信息
     * @param $id
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getEdit($id, Request $request)
    {
        // 获取当前用户信息
        $member = User::find($id);
        if (empty($member))
            return redirect()->back();
        // 是否越级操作
        if ($this->verifyIllegality($member->lv, $request))
            return butterflyAdminJump('error', getLang('Tips.illegal'));
        // 获取分组
        $group = UserAdminGroup::all()->keyBy('id');
        return view('butterfly::admin.manage.member-edit')->with(['group' => $group, 'member' => $member]);
    }
    public function postEdit($id, Request $request)
    {}

    public function getDel($id)
    {}

    /**
     * 上传图片
     * @param UploadImg $uploadImg
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImg(UploadImg $uploadImg, Request $request)
    {
        // 查找用户
        $member = User::find($request->input('id'));
        // 是否越级操作
        if ($member && $this->verifyIllegality($member->lv, $request))
            return butterflyAdminJump('error', getLang('Tips.illegal'));
        if ($request->hasFile('cropperFile'))
        {
            if ($request->file('cropperFile')->isValid())
            {
                $avatar_file = $request->file('cropperFile');
                $avatar_data = $request->input('cropperData');
                $avatar_data_obj = json_decode(stripslashes($avatar_data));
                $uploadImg->savePath = config('butterfly.upload.member_path').$request->input('uid').'/';
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
                    //更新数据
                    $check = User::where('id', $request->input('uid'))->update(['thumb' => $backData['data']['name']]);
                    if($check)
                    {
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