<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @ThemeHead(before)
    @ThemeHead(after)
    @stack('styles')
</head>
<body class="{{themeClass()}}">
    @ThemeBody(before)
    @yield('content')
    @ThemeBody(after)
    @stack('scripts')
</body>
</html>
