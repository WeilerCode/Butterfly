@extends('butterfly::admin.layout.admin')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $memberNum }}</h3>

                    <p>会员总数</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-secret"></i>
                </div>
                <a href="{{ route('admin-member-member') }}" class="small-box-footer">详细信息 <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $verifyMemberNum }}</h3>

                    <p>已验证会员</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-plus"></i>
                </div>
                <a href="{{ route('admin-member-member') }}" class="small-box-footer">详细信息 <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $logNum }}</h3>

                    <p>后台日志</p>
                </div>
                <div class="icon">
                    <i class="fa fa-terminal"></i>
                </div>
                <a href="{{ route('admin-manage-log') }}" class="small-box-footer">详细信息 <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $hdPercent }} <sup style="font-size: 20px">%</sup></h3>

                    <p>磁盘使用率</p>
                </div>
                <div class="icon">
                    <i class="fa fa-hdd-o"></i>
                </div>
                <div style="padding: 3px 0;">&nbsp;</div>
            </div>
        </div>
        <!-- ./col -->
    </div>
@endsection