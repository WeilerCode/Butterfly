@extends('butterfly::admin.layout.admin')

@section('css-plugins')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/cropper/cropper.min.css') }}">
@endsection

@section('js-plugins')
    <script src="{{ asset('vendor/butterfly/plugins/select2/js/select2.full.js') }}"></script>
    <script src="{{ asset('vendor/butterfly/plugins/cropper/cropper.min.js') }}"></script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/admin/css/cropper.css') }}">
@endsection

@section('js')
    <script src="{{ asset('vendor/butterfly/admin/js/cropper.js') }}"></script>
    <script>
        $(function () {
            $(".select2").select2();
            // 禁止上传头像后刷新页面
            toastr.options.isReload = false;
        })
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <a href="{{ route('admin-member-member') }}" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> {{ getLang('Tips.rollBack') }}</a>
                </div>
                <div class="box-body">
                    <form role="form" action="{{ route('admin-member-member-edit-post', ['id' => $member->id]) }}" method="post">
                        <img id="imgShow" class="profile-user-img img-responsive butterfly-cropper-click" src="{{ route('img-member', ['uid' => $member->id, 'sourceName' => $member->thumb, 'size' => '100x100']) }}" data-toggle="tooltip" data-original-title="点击更换头像">
                        {{ csrf_field() }}
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'groupID')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.group') }} <span class="asterisk">*</span></label>
                            <select name="groupID" data-placeholder="{{ getLang('fieldsAdminMember.groupHelp') }}" class="form-control select2" style="width: 100%;">
                                <option value=""></option>
                                @foreach($group as $v)
                                    <option value="{{ $v->id }}" @if($member->groupID == $v->id) selected @endif>{{ $v->name }}</option>
                                @endforeach
                            </select>
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'name')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.username') }}</label>
                            <input type="text" class="form-control" disabled value="{{ old('name') ? old('name') : $member->name }}" placeholder="{{ getLang('fieldsAdminMember.usernameHelp') }}">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'realName')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.nickname') }}</label>
                            <input type="text" class="form-control" name="realName" value="{{ old('realName') ? old('realName') : $member->realName }}" placeholder="">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'email')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.email') }} <span class="asterisk">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') ? old('email') : $member->email }}" placeholder="{{ getLang('fieldsAdminMember.emailHelp') }}">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'phone')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.phone') }}</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') ? old('phone') : $member->phone }}" placeholder="{{ getLang('fieldsAdminMember.phoneHelp') }}">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'password')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.password') }}</label>
                            <input type="password" class="form-control" name="password" value="" placeholder="不修改密码时请不要填写">
                            {!! $error !!}
                        </div>
                        <div class="form-group">
                            <label>{{ getLang('fieldsAdminMember.password2') }}</label>
                            <input type="password" class="form-control" name="password_confirmation" value="" placeholder="">
                        </div>
                        <button class="btn btn-success btn-sm btn-block" type="submit"><i class="fa fa-check"></i>&nbsp;{{ getLang('Tips.submit') }}</button>
                    </form>
                </div>
                <!-- /.box-body -->
                <div class="box-footer"></div>
            </div>
            <!-- /.box -->
        </div>
    </div>
    <div class="modal fade" id="butterfly-cropper-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin-member-member-uploadImg') }}" class="butterfly-cropper-form" enctype="multipart/form-data" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">图片上传</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="aspectRatioWidth" value="1">
                                <input type="hidden" id="aspectRatioHeight" value="1">
                                <input type="hidden" name="uid" value="{{ $member->id }}"/>
                                <input type="hidden" class="butterfly-cropper-data" name="cropperData">
                                <label class="btn btn-success btn-upload" for="inputImage" title="选择文件">
                                    <input type="file" class="butterfly-cropper-file" id="inputImage" name="cropperFile" >
                                    <span class="docs-tooltip">
                                    <span class="fa fa-image"></span>
                                </span>
                                </label>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary butterfly-cropper-event" data-method="move" title="移动">
                                        <span class="fa fa-arrows"></span>
                                    </button>
                                    <button type="button" class="btn btn-primary butterfly-cropper-event" data-method="crop" title="裁剪">
                                        <span class="fa fa-crop"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="butterfly-cropper-canvas"></div>
                            </div>
                            <div class="col-md-3">
                                <div class="butterfly-cropper-thumb-canvas abb"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-success"><span class="fa fa-upload"></span> 上传</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection