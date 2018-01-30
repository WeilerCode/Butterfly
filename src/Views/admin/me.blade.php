@extends('butterfly::admin.layout.admin')

@section('css-plugins')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/cropper/cropper.min.css') }}">
@endsection

@section('js-plugins')
    <script src="{{ asset('vendor/butterfly/plugins/cropper/cropper.min.js') }}"></script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/admin/css/cropper.css') }}">
@endsection

@section('js')
    <script src="{{ asset('vendor/butterfly/admin/js/cropper.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Image -->
            <div class="box box-success">
                <div class="box-body box-profile">
                    <img id="imgShow" class="profile-user-img img-responsive butterfly-cropper-click" src="{{ route('img-member', ['uid' => $USER->id, 'sourceName' => $USER->thumb, 'size' => '100x100']) }}" data-toggle="tooltip" data-original-title="点击更换头像">
                    <h3 class="profile-username text-center">{{ $USER->realName }}</h3>

                    <p class="text-muted text-center">{{ $A_GROUP[$USER->groupID]['name'] }}</p>

                    <form role="form" action="{{ route('admin-me-update') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'realName')){{ 'has-error' }}@endif">
                            <label>昵称</label>
                            <input type="text" class="form-control" name="realName" value="{{ $USER->realName }}" placeholder="昵称">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'email')){{ 'has-error' }}@endif">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $USER->email }}" placeholder="Email">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'phone')){{ 'has-error' }}@endif">
                            <label>手机</label>
                            <input type="text" class="form-control" name="phone" value="{{ $USER->phone }}" placeholder="手机号码">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'password')){{ 'has-error' }}@endif">
                            <label>密码</label>
                            <input type="password" class="form-control" name="password" placeholder="密码">
                            {!! $error !!}
                        </div>
                        <div class="form-group">
                            <label>重复密码</label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="重复密码">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">提交</button>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-8">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">事件日志</h3>
                </div>
                <div class="box-body">
                    <!-- The timeline -->
                    @if(count($log))
                        <?php
                        $color = ['bg-red', 'bg-yellow', 'bg-aqua', 'bg-blue', 'bg-light-blue', 'bg-green',
                            'bg-navy', 'bg-teal', 'bg-olive', 'bg-orange', 'bg-fuchsia', 'bg-purple', 'bg-maroon',
                            'bg-black'];
                        $day = strtotime(date('Y-m-d 00:00:00', $log[0]->created_at)) + 86400;
                        ?>
                        <ul class="timeline timeline-inverse">
                        @foreach($log as $v)
                            @if($day - $v->created_at > 0)
                                <!-- timeline time label -->
                                    <li class="time-label">
                                        <span class="{{ $color[rand(0,13)] }}">
                                          {{ date('Y-m-d', $v->created_at) }}
                                        </span>
                                    </li>
                                    <!-- /.timeline-label -->
                                <?php $day = strtotime(date('Y-m-d 00:00:00', $v->created_at)); ?>
                            @endif
                            <!-- timeline item -->
                                <li>
                                    <i class="{{ config('butterfly.admin_event_log.'.$v->type) ? config('butterfly.admin_event_log.'.$v->type)['style'] : 'fa fa-info-circle butterfly-event-bg-other' }}"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> {{ date('d H:i:s', $v->created_at) }}</span>

                                        <h3 class="timeline-header" style="color: #3c8dbc">{{ getLang($v->event) }}</h3>

                                        <div class="timeline-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <dl>
                                                        <dt>IP:</dt>
                                                        <dd>{{ $v->ip }}</dd>
                                                        <dt>IOS_CODE:</dt>
                                                        <dd>{{ $v->iso_code }}</dd>
                                                        <dt>城市:</dt>
                                                        <dd>{{ $v->city }}</dd>
                                                        @if($v->origin)<dt>事件发生前:</dt> <dd><pre>{{ jsonFormat(json_decode($v->origin)) }}</pre></dd>@endif
                                                        @if($v->ending)<dt>事件发生后:</dt> <dd><pre>{{ jsonFormat(json_decode($v->ending)) }}</pre></dd>@endif
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <!-- END timeline item -->
                            @endforeach
                            <li>
                                <i class="fa fa-clock-o bg-gray"></i>
                            </li>
                        </ul>
                    @endif
                </div>
                @if($log->hasPages())
                    <div class="box-footer">
                        {{ $log->links('butterfly::admin.pagination.default') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="butterfly-cropper-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin-me-uploadImg') }}" class="butterfly-cropper-form" enctype="multipart/form-data" method="post">
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