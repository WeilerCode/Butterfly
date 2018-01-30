@extends('butterfly::admin.layout.admin')

@section('css-plugins')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js-plugins')
    <script src="{{ asset('vendor/butterfly/plugins/select2/js/select2.full.js') }}"></script>
@endsection

@section('css')
@endsection

@section('js')
    <script>
        $(function () {
            $(".select2").select2();
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
                    <form role="form" action="{{ route('admin-member-member-add-post') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'groupID')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.group') }} <span class="asterisk">*</span></label>
                            <select name="groupID" data-placeholder="{{ getLang('fieldsAdminMember.groupHelp') }}" class="form-control select2" style="width: 100%;">
                                <option value=""></option>
                                @foreach($group as $v)
                                    <option value="{{ $v->id }}" @if(old('groupID') && old('groupID') == $v->id) selected @endif>{{ $v->name }}</option>
                                @endforeach
                            </select>
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'name')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.username') }} <span class="asterisk">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{ getLang('fieldsAdminMember.usernameHelp') }}">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'realName')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.nickname') }}</label>
                            <input type="text" class="form-control" name="realName" value="{{ old('realName') }}" placeholder="">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'email')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.email') }} <span class="asterisk">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ getLang('fieldsAdminMember.emailHelp') }}">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'phone')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.phone') }}</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="{{ getLang('fieldsAdminMember.phoneHelp') }}">
                            {!! $error !!}
                        </div>
                        <div class="form-group @if($error = getValidationErrorForTemplate($errors, 'password')){{ 'has-error' }}@endif">
                            <label>{{ getLang('fieldsAdminMember.password') }} <span class="asterisk">*</span></label>
                            <input type="password" class="form-control" name="password" value="" placeholder="">
                            {!! $error !!}
                        </div>
                        <div class="form-group">
                            <label>{{ getLang('fieldsAdminMember.password2') }} <span class="asterisk">*</span></label>
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
@endsection