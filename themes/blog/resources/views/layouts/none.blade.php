<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    @ThemeHead(before)
    @ThemeHead(after)
    @stack('styles')
    <style type="text/css">
        {!! themeOption('custom_css') !!}
    </style>
</head>

<body class="{{ themeClass() }}" :class="themeDark && 'theme-dark'" x-data="{ themeDark: false }">
    @ThemeBody(before)
    @yield('content')
    @ThemeBody(after)
    @stack('scripts')
    <script type="text/javascript">
        {!! themeOption('custom_js') !!}
    </script>
</body>

</html>
