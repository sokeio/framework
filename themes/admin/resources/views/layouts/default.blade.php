<!doctype html>
<html>

<head>
    @ThemeHead(before)
    @ThemeHead(after)
    @stack('styles')
</head>

<body class="{{ themeClass() }}">
    @ThemeBody(before)
    121212
    @yield('content')
    @ThemeBody(after)
    @stack('scripts')
</body>

</html>
