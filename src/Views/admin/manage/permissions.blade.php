@extends('butterfly::admin.layout.admin')

@section('css-plugins')
@endsection

@section('js-plugins')
@endsection

@section('css')
@endsection

@section('js')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <a href="{{ route('admin-manage-permissions-add-group') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> 创建</a>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th class="text-center border-right">{{ getLang('fieldsAdminGroup.level') }}</th>
                            <th class="text-center">{{ getLang('fieldsAdminGroup.groupName') }}</th>
                            <th class="text-center">{{ getLang('Tips.operation') }}</th>
                        </tr>
                        </thead>
                        @foreach($group as $v)
                            <tr>
                                <td class="text-center border-right">{{ $v->lv }}</td>
                                <td class="text-center">{{ $v->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin-manage-permissions-permissions', ['groupID' => $v->id]) }}" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="{{ getLang('Tips.authManage') }}"><i class="fa fa-get-pocket"></i></a>
                                    <a href="{{ route('admin-manage-permissions-edit-group', ['id' => $v->id]) }}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="{{ getLang('Tips.edit') }}"><i class="fa fa-pencil"></i></a>
                                    <button data-url="{{ route('admin-manage-permissions-del-group', ['id' => $v->id]) }}" data-tips='{{ getLang('Tips.isDelete') }} : {{ $v->name }} ; {{ getLang('fieldsAdminGroup.deleteTips') }}' class='btn btn-danger btn-xs delete-table' data-toggle="tooltip" data-placement="top" data-original-title="{{ getLang('Tips.delete') }}"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection