<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <link rel="stylesheet" type="text/css" href="<?=asset('public/css/app.css')?>"/>
    @yield('style')
    @yield('scripts')
</head>
<body>
@include('common.header')
@yield('content')
@include('common.footer')
</body>
</html>
