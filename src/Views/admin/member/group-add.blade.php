@extends('butterfly::admin.layout.admin')

@section('css-plugins')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('js-plugins')
    <script src="{{ asset('vendor/butterfly/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection

@section('css')
@endsection

@section('js')
    <script>
        $(function () {
            $('.myColor').colorpicker();
        })
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <a href="{{ route('admin-member-group') }}" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> {{ getLang('Tips.rollBack') }}</a>
                </div>
                <div class="box-body">
                    <form role="form" action="{{ route('admin-member-group-add-post') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'name')){{ 'has-error' }}@endif">
                            <label>分组名称 <span class="asterisk">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="分组名称-可填写本地化名称">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'lv')){{ 'has-error' }}@endif">
                            <label>等级 <span class="asterisk">*</span></label>
                            <input type="text" class="form-control" name="lv" value="{{ old('lv') }}" placeholder="等级,数字越大权限越大,必修为整数.">
                            {!! $error !!}
                        </div>
                        <div class="form-group myColor @if($error = getValidationErrorForTemplate($errors, 'color')){{ 'has-error' }}@endif">
                            <label>颜色 </label>
                            <div class="input-group myColor col-xs-6">
                                <input type="text" name="color" value="{{ old('color') ? old('color') : '#c1002e' }}" class="form-control"/>
                                <div class="input-group-addon"><i></i></div>
                            </div>
                            {!! $error !!}
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
@endsection