<!doctype html>
<html>
<head>
    @ThemeHead(before)
    @ThemeHead(after)
    @stack('styles')
</head>
<body class="{{theme_class()}}">
    @ThemeBody(before)
    @yield('content')
    @ThemeBody(after)
    @stack('scripts')
</body>
</html>
