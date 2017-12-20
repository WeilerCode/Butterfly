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
            $("[type='checkbox']").bootstrapSwitch();
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th class="text-center border-right" style="width: 5%">{{ __('butterfly::Tips.sort') }}</th>
                            <th class="text-center">{{ __('butterfly::fieldsMenu.icon') }}</th>
                            <th>{{ __('butterfly::fieldsMenu.menuName') }}</th>
                            <th class="text-center">{{ __('butterfly::fieldsMenu.display') }}</th>
                            <th>{{ __('butterfly::fieldsMenu.routeName') }}</th>
                            <th class="text-center" style="width: 12%;">{{ __('butterfly::Tips.operation') }}</th>
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