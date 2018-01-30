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
        $members = $members->orderBy('id', 'desc')->paginate(20);
        // 获取会员分组
        $group = UserMemberGroup::all()->keyBy('id');
        return view('butterfly::admin.member.member')->with(['members' => $members, 'group' => $group]);
    }

    public function getAdd()
    {}
    public function postAdd(Request $request)
    {}

    public function getEdit($id)
    {}
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
                    // 更新前
                    $origin = User::where('id', $request->input('uid'))->first();
                    //更新数据
                    $check = User::where('id', $request->input('uid'))->update(['thumb' => $backData['data']['name']]);
                    if($check)
                    {
                        // setLog
                        $this->setLog($request->user()->id, 'update', 'adminLogEvent.manage.member.uploadImg', json_encode($origin), json_encode($backData));
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