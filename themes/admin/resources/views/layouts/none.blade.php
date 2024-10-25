<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @themeHead
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" value="{{ csrf_token() }}"/>
</head>

<body :data-bs-theme="themeDark && 'dark'" x-data="{ themeDark: false, toggleTheme() { this.themeDark = !this.themeDark } }">
    @themeBody
    @yield('content')
    @themeBodyEnd
</body>

</html>
