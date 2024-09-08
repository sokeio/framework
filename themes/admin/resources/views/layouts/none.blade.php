<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @themeHead
    @csrf
</head>

<body :data-bs-theme="themeDark && 'dark'" x-data="{ themeDark: false, toggleTheme() { this.themeDark = !this.themeDark } }">
    @themeBody
    @yield('content')
    @themeBodyEnd
</body>

</html>
