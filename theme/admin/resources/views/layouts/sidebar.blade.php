<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @themeHead
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" value="{{ csrf_token() }}" />
</head>

<body x-data="sokeioBody()">
    @themeBody
    <div class="page">
        @themeInclude('shared.sidebar')
        @themeInclude('shared.sidebar-header')
        <div class="page-wrapper">
            @themeInclude('shared.content')
            @themeInclude('shared.footer')
        </div>
    </div>
    @themeBodyEnd
</body>

</html>
