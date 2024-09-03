<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @themeHead
    @csrf
</head>

<body>
    @themeBody
    <div class="page">
        @themeInclude('partials.header')
        <div class="page-wrapper">
            @yield('content')
        </div>
        @themeInclude('partials.footer')
    </div>
    @themeBodyEnd
</body>

</html>
