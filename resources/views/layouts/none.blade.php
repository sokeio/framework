<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @themeHead
    <meta name="csrf_token" value="{{ csrf_token() }}"/>
</head>

<body>
    @themeBody
    @yield('content')
    @themeBodyEnd
</body>

</html>
