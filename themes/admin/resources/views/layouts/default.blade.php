<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @themeHead
    <meta name="csrf_token" value="{{ csrf_token() }}"/>
</head>

<body :data-bs-theme="themeDark && 'dark'"
x-data="{ themeDark: false, toggleTheme() {  this.themeDark = !this.themeDark; }}">
    @themeBody
    <div class="page">
        @themeInclude('shared.header')
        <div class="page-wrapper">
            @yield('content')
        </div>
        @themeInclude('shared.footer')
    </div>
    @themeBodyEnd
</body>

</html>
