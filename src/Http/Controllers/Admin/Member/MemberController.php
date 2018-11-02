<?php

namespace Weiler\Butterfly\Http\Controllers\Admin\Member;

use Illuminate\Http\Request;
use Weiler\Butterfly\Http\Controllers\AdminController;
use Weiler\Butterfly\Models\User;
use Weiler\Butterfly\Models\UserMemberGroup;
use Weiler\UploadImg\UploadImg;

class MemberController extends AdminController
{
    /**
     * 会员管理
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        // 获取会员列表
        $members = User::where('type', 'member');
        // filtrate分组
        if($request->has('groupID')) {
            $members->where('groupID', $request->input('groupID'));
        }
        // filtrate时间段
        if($request->has('time')) {
            $time = explode(' - ',$request->input('time'));
            if(isset($time[0]) && isset($time[1])) {
                $startime = strtotime($time[0]);
                $endtime = strtotime($time[1]);
                $members->where('updated_at','>=',$startime)->where('updated_at','<=',$endtime);
            }
        }
        // filtrate搜索
        if($request->has('searchType') && $request->has('keyword')) {
            switch ($request->input('searchType')) {
                case 'id':
                    $members->where('id',$request->input('keyword'));
                    break;
                case 'email':
                case 'name':
                case 'realName':
                    $members->where($request->input('searchType'), 'LIKE', '%'.$request->input('keyword').'%');
                    break;
            }
        }
        // 用户总数
        $sum = $members->count();
        $members = $members->orderBy('id', 'desc')->paginate(20);
        // 获取会员分组
        $group = UserMemberGroup::all()->keyBy('id');
        return view('butterfly::admin.member.member')->with(['members' => $members, 'group' => $group, 'sum' => $sum]);
    }

    /**
     * 创建会员
     * @return $this
     */
    public function getAdd()
    {
        // 获取分组
        $group = UserMemberGroup::orderBy('lv', 'asc')->get()->keyBy('id');
        return view('butterfly::admin.member.member-add')->with(['group' => $group]);
    }
    public function postAdd(Request $request)
    {
        // 验证条件
        $rule = [
            'groupID'       =>  'required',
            'name'          =>  'required|unique:butterfly_users',
            'email'         =>  'required|email|max:255|unique:butterfly_users',
            'password'      =>  'required|confirmed|min:6'
        ];
        // 表单验证
        $validator = $this->validator($request->input(), $rule);
        if ($validator)
            return redirect()->back()->withErrors($validator)->withInput();
        // 获取对应分组信息
        $group = UserMemberGroup::all()->keyBy('id');
        $data = [
            'type'      =>  "member",
            'lv'        =>  $group[$request->input('groupID')]->lv,
            'name'      =>  $request->input('name'),
            'realName'  =>  $request->input('realName') ? $request->input('realName') : '',
            'thumb'     =>  '',
            'email'     =>  $request->input('email'),
            'phone'     =>  $request->input('phone') ? $request->input('phone') : '',
            'verify'    =>  1,
            'verifyTime'=>  time(),
            'groupID'   =>  $request->input('groupID'),
            'password'  =>  bcrypt($request->input('password')),
            'api_token' =>  str_random(28).md5(time())
        ];
        if (isset($group[$request->input('groupID')]) && User::create($data)) {
            // setLog
            $this->setLog($request->user()->id, 'create', 'adminLogEvent.member.member.add', NULL, json_encode($data));
            return butterflyAdminJump('success', getLang('Tips.createSuccess'), route('admin-member-member'), 1);
        }
        return butterflyAdminJump('error', getLang('Tips.createFail'), route('admin-member-member'), 1);
    }

    /**
     * 修改会员信息
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function getEdit($id)
    {
        // 获取当前用户信息
        $member = User::find($id);
        if (empty($member))
            return redirect()->back();
        // 是否越级操作
        if ($member->type == "system")
            return butterflyAdminJump('error', getLang('Tips.illegal'));
        // 获取分组
        $group = UserMemberGroup::orderBy('lv', 'asc')->get()->keyBy('id');
        return view('butterfly::admin.member.member-edit')->with(['group' => $group, 'member' => $member]);
    }
    public function postEdit($id, Request $request)
    {
        //修改前的值
        $origin = User::find($id)->toArray();
        // 是否越级操作
        if ($origin['type'] == "system")
            return butterflyAdminJump('error', getLang('Tips.illegal'));
        // 验证条件
        $rule = [
            'groupID'       =>  'required',
            'email'         =>  'email|max:255|unique:butterfly_users,email,'.$id
        ];
        if ($request->input('password'))
            $rule['password'] = 'required|confirmed|min:6';
        // 表单验证
        $validator = $this->validator($request->input(), $rule);
        if ($validator)
            return redirect()->back()->withErrors($validator)->withInput();

        // 获取group
        $group = UserMemberGroup::all()->keyBy('id');
        // 组织数据
        $data = [
            'lv'        =>  $group[$request->input('groupID')]->lv,
            'realName'  =>  $request->input('realName') ? $request->input('realName') : '',
            'email'     =>  $request->input('email') ? $request->input('email') : '',
            'phone'     =>  $request->input('phone') ? $request->input('phone') : '',
            'groupID'   =>  $request->input('groupID'),
            'password'  =>  bcrypt($request->input('password'))
        ];
        // 判断是否修改password
        if ($request->input('password'))
            $data['password'] = $request->input('password');
        if (isset($group[$request->input('groupID')]) && User::where('id', $id)->update($data)) {
            // setLog
            $this->setLog($request->user()->id, 'update', 'adminLogEvent.member.member.edit', json_encode($origin), json_encode($data));
            return butterflyAdminJump('success', getLang('Tips.updateSuccess'), route('admin-member-member-edit', ['id' => $id]), 1);
        }
        return butterflyAdminJump('error', getLang('Tips.updateFail'), route('admin-member-member-edit', ['id' => $id]), 1);
    }

    /**
     * 删除用户
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDel($id, Request $request)
    {
        // 不得删除root用户
        if ((int)$id === 1)
            return butterflyAdminJump('error', getLang('Tips.userNotDelete'),'',1);
        // 获取用户
        $user = User::find($id);
        if (!empty($user))
        {
            // 是否越级操作
            if ($user->type == "system")
                return butterflyAdminJump('error', getLang('Tips.illegal'));
            // 删除前
            $origin = $user->toArray();
            if ($user->delete())
            {
                // setLog
                $this->setLog($request->user()->id, 'delete', 'adminLogEvent.member.member.del', json_encode($origin), NULL);
                return butterflyAdminJump('success', getLang('Tips.deleteSuccess'),'',1);
            }
        }
        return butterflyAdminJump('error', getLang('Tips.illegal'),'',1);
    }

    /**
     * 上传图片
     * @param UploadImg $uploadImg
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImg(UploadImg $uploadImg, Request $request)
    {
        // 更新前
        $origin = User::where('id', $request->input('uid'))->first();
        // 是否越级操作
        if ($origin['type'] == "system")
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
                        // setLog
                        $this->setLog($request->user()->id, 'update', 'adminLogEvent.member.member.uploadImg', json_encode($origin), json_encode($backData));
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