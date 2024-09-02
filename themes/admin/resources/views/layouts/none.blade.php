<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @themeHead
    @csrf
</head>

<body>
    @themeBody
    @yield('content')
    @themeBodyEnd
</body>

</html>
