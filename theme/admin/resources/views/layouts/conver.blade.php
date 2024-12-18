<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @themeHead
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" value="{{ csrf_token() }}" />
</head>

<body x-data="sokeioBody()">
    @themeBody
    <div class="vh-100 d-flex flex-column bg-white">
        <div class="row g-0 flex-fill">
            <div class="col-12 col-lg-6 col-xl-8 d-none d-lg-block">
                <!-- Photo -->
                <div class="bg-cover h-100 min-vh-100"
                    style="background-image: url({{ setting('SOKEIO_SYSTEM_ADMIN_LOGIN_COVER_IMAGE', asset('platform/module/sokeio/cover.jpg')) }})">
                </div>
            </div>
            <div
                class="col-12 col-lg-6 col-xl-4 border-top-wide border-primary d-flex flex-column justify-content-center">
                <div class="container container-tight mt-4 px-lg-5">
                    <div class="text-center mt-3 mb-6">
                        <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark"><img
                                src="{{ platform()->getSystemLogo() }}" class="rounded-2" height="100"
                                alt="{{ platform()->getSystemName() }}"></a>
                    </div>
                    @yield('content')
                </div>
                <div class="text-center text-muted mt-auto mb-2">
                    <div class=mb-2">
                        <a href="https://sokeio.com" class="text-reset" title="Sokeio Technology">
                            Sokeio Technology
                        </a> V1.0
                    </div>
                    Copyright &copy; {{ date('Y') }}. All rights reserved.
                </div>
            </div>
        </div>
    </div>

    @themeBodyEnd
</body>

</html>
