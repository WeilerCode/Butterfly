@extends('butterfly::admin.layout.admin')

@section('css-plugins')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') }}">
@endsection

@section('js-plugins')
    <script src="{{ asset('vendor/butterfly/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
@endsection

@section('css')
@endsection

@section('js')
    <script>
        $(function () {
            $(".switch").bootstrapSwitch();

            $(".switch").on('switchChange.bootstrapSwitch', function(event, state) {
                var id = $(this).data('id');
                var display = 0;
                if(state)
                    display = 1;
                $.ajax({
                    url: "{{ route('admin-manage-menu-display') }}",
                    data: { id:id,display:display},
                    type:'post',
                    dataType: 'json',
                    success: function (data) {
                        if (data.result === 'OK')
                        {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                        }
                    }
                })
            });
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-sort-amount-asc"></i> 排序</button>
                    <a href="{{ route('admin-manage-menu-add') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> 创建</a>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th class="text-center border-right" style="width: 5%">{{ getLang('Tips.sort') }}</th>
                            <th class="text-center">{{ getLang('fieldsMenu.icon') }}</th>
                            <th>{{ getLang('fieldsMenu.menuName') }}</th>
                            <th class="text-center">{{ getLang('fieldsMenu.display') }}</th>
                            <th>{{ getLang('fieldsMenu.routeName') }}</th>
                            <th class="text-center" style="width: 12%;">{{ getLang('Tips.operation') }}</th>
                        </tr>
                        </thead>
                        {!! $tree !!}
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

        </div>
    </div>
@endsection