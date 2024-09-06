<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @themeHead
    @csrf
</head>

<body>
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
