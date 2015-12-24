<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <link rel="stylesheet" type="text/css" href="<?=asset('public/css/app.css')?>"/>
    <script src="<?=asset('public/js/app.js')?>"></script>
    @yield('style')
    @yield('scripts')
</head>
<body>
@include('common.header')
@yield('content')
@include('common.footer')
</body>
</html>
