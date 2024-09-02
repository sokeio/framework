<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @themeHead
    @csrf
</head>

<body>
    @themeBody
    @themeInclude('partials.header')
    @yield('content')
    @themeInclude('partials.footer')
    @themeBodyEnd
</body>

</html>
