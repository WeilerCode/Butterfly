<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="{{ $jumpSeconds }};url={{ $jumpUrl }}">
    <title>{{ $title }}</title>
    <!-- Sweet Alert -->
    <link href="{{ asset('vendor/butterfly/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
</head>

<body>
<div class="sweet-overlay" style="opacity: 0.5; display: block;"></div>
<div class="sweet-alert showSweetAlert visible" data-custom-class="" data-has-cancel-button="false" data-has-confirm-button="true" data-allow-outside-click="false" data-has-done-function="false" data-animation="pop" data-timer="null" style="display: block; margin-top: -170px;">
    @yield('content')
    <h2>{{ $title }}</h2>
    <p style="display: block;">{{ $jumpMsg }}</p>
    <div class="sa-button-container">
        <button class="confirm" onclick="location='{{ $jumpUrl }}'">跳转</button>
    </div>
</div>
</body>
</html>