<!DOCTYPE html>
<html lang="{{ getLang('htmlTag.lang') == 'htmlTag.lang' ? 'en' : getLang('htmlTag.lang') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Butterfly | {{ getLang(last($B_MENU)['name']) }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/plugins/toastr/toastr.min.css') }}">
    @yield('css-plugins')
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/admin/AdminLTE/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/admin/AdminLTE/css/skins/skin-green.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/butterfly/admin/css/main.css') }}">
    @yield('css')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-green sidebar-mini fixed layout-boxed">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="{{ route('admin-index') }}" class="logo hidden-xs">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>BF</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Butterfly</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ route('img-member', ['uid' => $USER->id, 'sourceName' => $USER->thumb, 'size' => '100x100']) }}" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ $USER->realName }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ route('img-member', ['uid' => $USER->id, 'sourceName' => $USER->thumb, 'size' => '100x100']) }}" class="img-circle" alt="User Image">

                                <p>
                                    {{ $USER->realName }}
                                    <small>{{ $A_GROUP[$USER->groupID]['name'] }}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ route('admin-me') }}" class="btn btn-default btn-flat">我的面板</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ action('\Weiler\Butterfly\Http\Controllers\Admin\Auth\AuthController@logout') }}" class="btn btn-default btn-flat">退出</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ route('img-member', ['uid' => $USER->id, 'sourceName' => $USER->thumb, 'size' => '100x100']) }}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ $USER->realName }}</p>
                    <small>{{ $A_GROUP[$USER->groupID]['name'] }}</small>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">主导航</li>
                @foreach($F_MENU as $f)
                    @if(!empty($S_MENU[$f['id']]))
                        @if($f['display'] === 1)
                            <li class="treeview @if(isset($f['active']) && $f['active'] == 1) active @endif">
                                <a href="javascript:void(0);">
                                    <i class="{{ $f['icon'] }}"></i> <span>{{ getLang($f['name']) }}</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                @if(!empty($S_MENU[$f['id']]))
                                    <ul class="treeview-menu">
                                        @foreach($S_MENU[$f['id']] as $s)
                                            @if($s['display'] === 1)
                                                @if(isset($s['current']))
                                                    <li @if(isset($s['active']) && $s['active'] == 1) class="active" @endif><a href="javascript:void(0);"><i class="fa fa-circle-o"></i> {{ getLang($s['name']) }}</a></li>
                                                @else
                                                    <li @if(isset($s['active']) && $s['active'] == 1) class="active" @endif><a href="{{ $s['url'] }}"><i class="fa fa-circle-o"></i> {{ getLang($s['name']) }}</a></li>
                                                @endif
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @else
                        @if($f['display'] === 1)
                            @if(isset($f['active']) && $f['active'] == 1)
                                <li class="active">
                                    <a href="javascript:void(0);">
                                        <i class="{{ $f['icon'] }}"></i> <span>{{ getLang($f['name']) }}</span>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $f['url'] }}">
                                        <i class="{{ $f['icon'] }}"></i> <span>{{ getLang($f['name']) }}</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endif
                @endforeach
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="{{ head($B_MENU)['icon'] }}"></i> {{ getLang(last($B_MENU)['name']) }}</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                @foreach($B_MENU as $v)
                    @if(isset($v['current']))
                        <li class="active">
                            {{ getLang($v['name']) }}
                        </li>
                    @else
                        <li>
                            <a href="@if(!empty($v['routeName'])){{ route($v['routeName']) }}@else # @endif">{{ getLang($v['name']) }}</a>
                        </li>
                    @endif
                @endforeach
            </ol>
        </section>
        <section class="content">
            @yield('content')
        </section>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> alpha
        </div>
        <strong>Create by <a href="mailto:weiler.china@gmail.com">Weiler</a> & <a href="https://adminlte.io">Almsaeed Studio</a>.</strong>
    </footer>
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('vendor/butterfly/plugins/jQuery/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('vendor/butterfly/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('vendor/butterfly/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('vendor/butterfly/plugins/fastclick/lib/fastclick.js') }}"></script>
<script src="{{ asset('vendor/butterfly/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('vendor/butterfly/plugins/bootbox/bootbox.min.js') }}"></script>
@yield('js-plugins')
<!-- AdminLTE App -->
<script src="{{ asset('vendor/butterfly/admin/AdminLTE/js/adminlte.min.js') }}"></script>
<script src="{{ asset('vendor/butterfly/admin/js/main.js') }}"></script>
@yield('js')
</body>
</html>