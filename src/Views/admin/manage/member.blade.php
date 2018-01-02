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
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <a href="{{ route('admin-manage-member-add') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> 创建</a>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th class="text-center border-right">{{ getLang('fieldsAdminMember.id') }}</th>
                            <th class="text-center border-right">头像</th>
                            <th class="text-center border-right">{{ getLang('fieldsAdminMember.nickname') }}</th>
                            <th class="text-center border-right">{{ getLang('fieldsAdminMember.username') }}</th>
                            <th class="text-center border-right">{{ getLang('fieldsAdminMember.group') }}</th>
                            <th class="text-center border-right">{{ getLang('fieldsAdminMember.email') }}</th>
                            <th class="text-center border-right">{{ getLang('fieldsAdminMember.lastLogin') }}</th>
                            <th class="text-center">{{ getLang('Tips.operation') }}</th>
                        </tr>
                        </thead>
                        @foreach($members as $v)
                            <tr>
                                <td class="text-center border-right">{{ $v->id }}</td>
                                <td class="text-center border-right">
                                    <img src="{{ route('img-member', ['uid' => $v->id, 'sourceName' => $v->thumb, 'size' => '22x22']) }}" width="22" alt="{{ $v->realName ? $v->realName : $v->name }}">
                                </td>
                                <td class="text-center border-right">{{ $v->realName ? $v->realName : $v->name }}</td>
                                <td class="text-center border-right">{{ $v->name }}</td>
                                <td class="text-center border-right"><span class="label" style="background-color: {{ $group[$v->groupID]->color }}">{{ $group[$v->groupID]->name }}</span></td>
                                <td class="text-center border-right">{{ $v->email }}</td>
                                <td class="text-center border-right">{{ $v->updated_at }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin-manage-member-edit', ['id' => $v->id]) }}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="{{ getLang('Tips.edit') }}"><i class="fa fa-pencil"></i></a>
                                    <button data-url="{{ route('admin-manage-member-del', ['id' => $v->id]) }}" data-tips='{{ getLang('Tips.isDelete') }} : {{ $v->realName ? $v->realName : $v->name }}' class='btn btn-danger btn-xs delete-table' data-toggle="tooltip" data-placement="top" data-original-title="{{ getLang('Tips.delete') }}"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {{ $members->links('butterfly::admin.pagination.default') }}
                </div>
            </div>
            <!-- /.box -->

        </div>
    </div>
@endsection