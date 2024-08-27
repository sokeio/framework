<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @themeHead
</head>
<body>
    @themeBody
    @yield('content')
    @themeBodyEnd
</body>

</html>
