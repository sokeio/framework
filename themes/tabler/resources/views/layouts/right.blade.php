<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    @ThemeHead(before)
    @ThemeHead(after)
    @stack('styles')
</head>

<body class="{{ themeClass() }}">
    @ThemeBody(before)
    <div class="page">
        @include('theme::share.header')
        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row g-0">
                        <div class="col-12 col-md-auto">
                            {!! themePosition('THEME_LEFT') !!}
                        </div>
                        <div class="col-12 col-md">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('theme::share.footer')
    </div>
    @ThemeBody(after)
    @stack('scripts')
</body>

</html>
