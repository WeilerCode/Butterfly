@extends('butterfly::admin.layout.admin')

@section('css-plugins')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/cropper/cropper.min.css') }}">
@endsection

@section('js-plugins')
    <script src="{{ asset('vendor/butterfly/plugins/toastr/toastr.min.js') }}"></script>
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
                    <ul class="timeline timeline-inverse">
                        <!-- timeline time label -->
                        <li class="time-label">
                        <span class="bg-red">
                          10 Feb. 2014
                        </span>
                        </li>
                        <!-- /.timeline-label -->
                        <!-- timeline item -->
                        <li>
                            <i class="fa fa-envelope bg-blue"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                                <div class="timeline-body">
                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                    weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                    jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                    quora plaxo ideeli hulu weebly balihoo...
                                </div>
                                <div class="timeline-footer">
                                    <a class="btn btn-primary btn-xs">Read more</a>
                                    <a class="btn btn-danger btn-xs">Delete</a>
                                </div>
                            </div>
                        </li>
                        <!-- END timeline item -->
                        <!-- timeline item -->
                        <li>
                            <i class="fa fa-user bg-aqua"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                                <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                                </h3>
                            </div>
                        </li>
                        <!-- END timeline item -->
                        <!-- timeline item -->
                        <li>
                            <i class="fa fa-comments bg-yellow"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                                <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                                <div class="timeline-body">
                                    Take me to your leader!
                                    Switzerland is small and neutral!
                                    We are more like Germany, ambitious and misunderstood!
                                </div>
                                <div class="timeline-footer">
                                    <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                                </div>
                            </div>
                        </li>
                        <!-- END timeline item -->
                        <!-- timeline time label -->
                        <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                        </li>
                        <!-- /.timeline-label -->
                        <!-- timeline item -->
                        <li>
                            <i class="fa fa-camera bg-purple"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                                <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                                <div class="timeline-body">
                                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                                </div>
                            </div>
                        </li>
                        <!-- END timeline item -->
                        <li>
                            <i class="fa fa-clock-o bg-gray"></i>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="butterfly-cropper-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin-me-uploadImg') }}" class="butterfly-cropper-form" enctype="multipart/form-data" method="post">
                    {!! csrf_field() !!}
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