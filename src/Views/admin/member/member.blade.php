@extends('butterfly::admin.layout.admin')

@section('css-plugins')
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('js-plugins')
    <script src="{{ asset('vendor/butterfly/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('vendor/butterfly/plugins/select2/js/select2.full.js') }}"></script>
    <script src="{{ asset('vendor/butterfly/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/butterfly/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('css')
@endsection

@section('js')
    <script>
        $(function () {
            $(".select2").select2().change(function(){
                getQuery();
            });

            // 选择搜索框
            var menu = $(".dropdown-menu").find("a");
            menu.click(function(){
                var text = $(this).text();
                var k = $(this).data('key');
                $('#showKey').text(text);
                $('#searchType').val(k);
            });

            var $groupID        = $("select[name='groupID']");
            var $time           = $("input[name='time']");
            function getQuery() {
                var groupID         = $groupID.val();
                var time            = $time.val();
                var separator       = '?';
                var url = '{{ route('admin-member-member') }}';
                if (groupID !== '' && groupID !== undefined)
                {
                    url += separator + 'groupID=' + groupID;
                    separator = '&';
                }
                if (time !== '' && time !== undefined)
                {
                    url += separator + 'time=' + time;
                }
                window.location = url;
            }
            $("#section").daterangepicker({
                "autoUpdateInput": false,
                "timePicker": true,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "timePickerIncrement": 1,
                "locale": {
                    "format": "YYYY-MM-DD HH:mm:ss",
                    "separator": " - ",
                    "applyLabel": "提交",
                    "cancelLabel": "取消",
                    "fromLabel": "From",
                    "toLabel": "To",
                    "customRangeLabel": "Custom",
                    "daysOfWeek": [
                        "日",
                        "一",
                        "二",
                        "三",
                        "四",
                        "五",
                        "六"
                    ],
                    "monthNames": [
                        "一月",
                        "二月",
                        "三月",
                        "四月",
                        "五月",
                        "六月",
                        "七月",
                        "八月",
                        "九月",
                        "十月",
                        "十一月",
                        "十二月"
                    ],
                    "firstDay": 1,
                },
                "drops": "down"
            }, function(start, end, label) {
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss') + " - " + picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
                getQuery();
            });
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="row" id="filtrate">
                        <div class="col-sm-2">
                            <select name="groupID" class="form-control select2" style="width: 100%;">
                                <option value="">全部分组</option>
                                @foreach($group as $v)
                                    <option value="{{ $v->id }}" @if(isset($_GET['groupID']) && $_GET['groupID'] == $v->id) selected @endif>{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="time" value="@if(isset($_GET['time']) && $_GET['time'] != ''){{ $_GET['time'] }}@endif" class="form-control pull-right" id="section" placeholder="最后登录时间段">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <form method="get">
                                <div class="input-group mb-15">
                                    <div class="input-group-btn">
                                        <?php
                                        function getKeyName($name){
                                            switch ($name){
                                                case 'id':
                                                    $keyName = getLang('fieldsMember.id');
                                                    break;
                                                case 'email':
                                                    $keyName = getLang('fieldsMember.emailHelp');
                                                    break;
                                                case 'name':
                                                    $keyName = getLang('fieldsMember.username');
                                                    break;
                                                case 'realName':
                                                    $keyName = getLang('fieldsMember.nickname');
                                                    break;
                                                default:
                                                    $keyName = getLang('fieldsMember.id');
                                            }
                                            return $keyName;
                                        }
                                        ?>
                                        <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button"><span id="showKey">{{ isset($_GET['searchType']) ? getKeyName($_GET['searchType']) : 'ID' }}</span> <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a data-key="id">{{ getLang('fieldsMember.id') }}</a></li>
                                            <li><a data-key="email">{{ getLang('fieldsMember.emailHelp') }}</a></li>
                                            <li><a data-key="name">{{ getLang('fieldsMember.username') }}</a></li>
                                            <li><a data-key="realName">{{ getLang('fieldsMember.nickname') }}</a></li>
                                        </ul>
                                    </div>
                                    <input id="searchType" name="searchType" value="{{ isset($_GET['searchType']) ? $_GET['searchType'] : 'id' }}" type="hidden">
                                    <input name="keyword" value="{{ isset($_GET['keyword']) ? $_GET['keyword'] : '' }}" class="form-control no-border-left" type="text">
                                    <span class="input-group-btn"><button type="submit" class="btn btn-success"><i id="icoico" class="fa fa-search"></i></button></span>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-success">会员数</button>
                                </div>
                                <!-- /btn-group -->
                                <input type="text" value="{{ $sum }}" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <a href="{{ route('admin-member-member-add') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> 创建</a>
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
                                <td class="text-center border-right">{{ $v->realName }}</td>
                                <td class="text-center border-right">{{ $v->name }}</td>
                                <td class="text-center border-right"><span class="label" style="background-color: {{ isset($group[$v->groupID]) ? $group[$v->groupID]->color : '#000' }}">{{ isset($group[$v->groupID]) ? $group[$v->groupID]->name : '未知' }}</span></td>
                                <td class="text-center border-right">{{ $v->email }}</td>
                                <td class="text-center border-right">{{ $v->updated_at }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin-member-member-edit', ['id' => $v->id]) }}" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="{{ getLang('Tips.edit') }}"><i class="fa fa-pencil"></i></a>
                                    <button data-url="{{ route('admin-member-member-del', ['id' => $v->id]) }}" data-tips='{{ getLang('Tips.isDelete') }} : {{ $v->realName ? $v->realName : $v->name }}' class='btn btn-danger btn-xs delete-table' data-toggle="tooltip" data-placement="top" data-original-title="{{ getLang('Tips.delete') }}"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->
                @if($members->hasPages())
                <div class="box-footer">
                    {{ $members->links('butterfly::admin.pagination.default') }}
                </div>
                @endif
            </div>
            <!-- /.box -->

        </div>
    </div>
@endsection