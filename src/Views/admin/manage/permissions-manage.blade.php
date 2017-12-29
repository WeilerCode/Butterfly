@extends('butterfly::admin.layout.admin')

@section('css-plugins')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/iCheck/all.css') }}">
@endsection

@section('js-plugins')
    <script src="{{ asset('vendor/butterfly/plugins/iCheck/icheck.min.js') }}"></script>
@endsection

@section('css')
@endsection

@section('js')
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green checkThis',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            // 根据等级选择
            $(".iCheck-helper",".checkThis").click(function ()
            {
                //var obj = this;
                var chk = $(".iCheck-helper",".checkThis");
                var count = chk.length;
                var num = chk.index(this);
                var chkinput = $("input[type='checkbox']",".checkThis");

                var level_top = level_bottom =  chkinput.eq(num).attr('level')
                for (var i=num; i>=0; i--)
                {
                    var le = chkinput.eq(i).attr('level');
                    if(eval(le) < eval(level_top))
                    {
                        chkinput.eq(i).iCheck("check");
                        var level_top = level_top-1;
                    }
                }
                for (var j=num+1; j<count; j++)
                {
                    var le = chkinput.eq(j).attr('level');
                    if(chkinput.eq(num).prop("checked")) {
                        if(eval(le) > eval(level_bottom))
                            chkinput.eq(j).iCheck("check");
                        else if(eval(le) == eval(level_bottom))
                            break;
                    }
                    else {
                        if(eval(le) > eval(level_bottom))
                            chkinput.eq(j).iCheck("uncheck");
                        else if(eval(le) == eval(level_bottom))
                            break;
                    }
                }
            });
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <form action="{{ route('admin-manage-permissions-permissions-post', ['groupID' => $groupID]) }}" method="post">
                    {!! csrf_field() !!}
                    <div class="box-header with-border">
                        <a href="{{ route('admin-manage-permissions') }}" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> {{ getLang('Tips.rollBack') }}</a>
                        <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i>&nbsp;{{ getLang('Tips.submit') }}</button>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="text-center border-right">选择</th>
                                <th class="text-center border-right">ICON</th>
                                <th class="text-center">Action name</th>
                            </tr>
                            </thead>
                            {!! $tree !!}
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer with-border">
                        <a href="{{ route('admin-manage-permissions') }}" class="btn btn-default btn-sm"><i class="fa fa-reply"></i> {{ getLang('Tips.rollBack') }}</a>
                        <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i>&nbsp;{{ getLang('Tips.submit') }}</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection