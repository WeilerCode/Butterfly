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
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body">
                    <!-- The timeline -->
                    @if(count($log))
                        <?php
                            $color = ['bg-red', 'bg-yellow', 'bg-aqua', 'bg-blue', 'bg-light-blue', 'bg-green',
                                'bg-navy', 'bg-teal', 'bg-olive', 'bg-orange', 'bg-fuchsia', 'bg-purple', 'bg-maroon',
                                'bg-black'];
                            $day = strtotime(date('Y-m-d 00:00:00', $log[0]->created_at)) + 86400;
                        ?>
                        <ul class="timeline timeline-inverse">
                            @foreach($log as $v)
                                @if($day - $v->created_at > 0)
                                    <!-- timeline time label -->
                                    <li class="time-label">
                                        <span class="{{ $color[rand(0,13)] }}">
                                          {{ date('Y-m-d', $v->created_at) }}
                                        </span>
                                    </li>
                                    <!-- /.timeline-label -->
                                    <?php $day = strtotime(date('Y-m-d 00:00:00', $v->created_at)); ?>
                                @endif
                                <!-- timeline item -->
                                    <li>
                                        <i class="{{ config('butterfly.admin_event_log.'.$v->type) ? config('butterfly.admin_event_log.'.$v->type)['style'] : 'fa fa-info-circle butterfly-event-bg-other' }}"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock-o"></i> {{ date('d H:i:s', $v->created_at) }}</span>

                                            <h3 class="timeline-header" style="color: #3c8dbc">{{ getLang($v->event) }}</h3>

                                            <div class="timeline-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <dl>
                                                            <dt>执行者:</dt>
                                                            <dd>{{ $v->realName ? $v->realName : $v->name }}</dd>
                                                            <dt>IP:</dt>
                                                            <dd>{{ $v->ip }}</dd>
                                                            <dt>IOS_CODE:</dt>
                                                            <dd>{{ $v->iso_code }}</dd>
                                                            <dt>城市:</dt>
                                                            <dd>{{ $v->city }}</dd>
                                                            @if($v->origin)<dt>事件发生前:</dt> <dd><pre>{{ jsonFormat(json_decode($v->origin)) }}</pre></dd>@endif
                                                            @if($v->ending)<dt>事件发生后:</dt> <dd><pre>{{ jsonFormat(json_decode($v->ending)) }}</pre></dd>@endif
                                                        </dl>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- END timeline item -->
                            @endforeach
                            <li>
                                <i class="fa fa-clock-o bg-gray"></i>
                            </li>
                        </ul>
                    @endif
                </div>
                @if($log->hasPages())
                    <div class="box-footer">
                        {{ $log->links('butterfly::admin.pagination.default') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection