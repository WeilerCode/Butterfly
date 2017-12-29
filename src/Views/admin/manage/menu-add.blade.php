@extends('butterfly::admin.layout.admin')

@section('css-plugins')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js-plugins')
    <script src="{{ asset('vendor/butterfly/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('vendor/butterfly/plugins/select2/js/select2.full.js') }}"></script>
@endsection

@section('css')
    <style>
        .fontawesome-icon-list .fa-hover a{
            display: block;
            line-height: 32px;
            height: 32px;
            color: #636E7B;
            text-decoration: none;
            padding-left: 10px;
        }
        .fontawesome-icon-list .fa-hover a:hover{
            background-color: #EEEEEE;
        }
    </style>
@endsection

@section('js')
    <script>
        $(function () {
            $("[type='checkbox']").bootstrapSwitch();
            $(".select2").select2();

            $.leftMove = function () {
                var pageH = $(document.body).height();
                var scrollT = $(window).scrollTop();
                var windowWidth = $(window).width();
                if (windowWidth < 975)
                    return false;
                if (scrollT > 50 && scrollT < pageH-$('#leftMove').height()-50)
                {
                    var offset = scrollT - 50;
                    $('#leftMove').css({"position":"relative" ,"top": offset + "px"});
                } else {
                    $('#leftMove').removeAttr('style');
                }
            };

            $(".fa-hover").each(function(){
                $(this).click(function(){
                    var icoclass = $("i",this).attr("class");
                    $("#ico").attr("value",icoclass);
                    $("#icoico").attr("class",icoclass);
                })
            });
            $.leftMove();
            $(window).scroll(function () {
                $.leftMove();
            });
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4" id="leftMove">
            <div class="box box-success">
                <div class="box-header with-border">
                    <a href="{{ route('admin-manage-menu') }}" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> {{ getLang('Tips.rollBack') }}</a>
                </div>
                <div class="box-body">
                    <form role="form" action="{{ route('admin-manage-menu-add-post') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'parentID')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsMenu.parentDir') }}</label>
                            <select name="parentID" class="form-control select2" style="width: 100%;">
                                <option value="0">{{ getLang('fieldsMenu.topDir') }}</option>
                                {!! $tree !!}
                            </select>
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'name')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsMenu.columnName') }} <span class="asterisk">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{ getLang('fieldsMenu.columnNameHelp') }}">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'routeName')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsMenu.routeName') }}</label>
                            <input type="text" class="form-control" name="routeName" value="{{ old('routeName') }}" placeholder="{{ getLang('fieldsMenu.routeNameHelp') }}">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'display')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsMenu.isShow') }}</label><br>
                            <input type="checkbox" name="display" checked-false data-size="small" data-on-text="{{ getLang('Tips.showON') }}" data-off-text="{{ getLang('Tips.showOFF') }}" data-label-width="0" data-on-color="success" data-off-color="danger">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'listOrder')){{ 'has-error' }}@endif">
                            <label>{{ getLang('Tips.sort') }}</label>
                            <input name="listOrder" value="{{ old('listOrder') ? old('listOrder') : 0 }}" class="form-control" type="text" placeholder="{{ getLang('fieldsMenu.sortHelp') }}">
                            {!! $error !!}
                        </div>
                        <div class="form-group">
                            <label>{{ getLang('fieldsMenu.icon') }}&nbsp;&nbsp;</label><i id="icoico" class="{{ old('icon') ? old('icon') : '' }}"></i>
                            <input type="text" name="icon" value="{{ old('icon') ? old('icon') : '' }}" id="ico" class="form-control" placeholder="{{ getLang('fieldsMenu.iconHelp') }}">
                            <p class="help-block">{{ getLang('fieldsMenu.iconHelp2') }}</p>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">提交</button>
                    </form>
                </div>
                <!-- /.box-body -->
                <div class="box-footer"></div>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-8">
            <div class="box box-success">
                <div class="box-body">
                    @include('butterfly::admin.manage.menu-icon')
                </div>
                <!-- /.box-body -->
                <div class="box-footer"></div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection