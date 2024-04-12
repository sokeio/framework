<aside class="navbar navbar-vertical navbar-expand-lg navbar-transparent border-end">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            {!! applyFilters(
                'THEME_ADMIN_SIDEBAR_LOGO',
                ' <a href="' .
                    route('admin.dashboard') .
                    '"><span class=" fw-bold fs-1 p-1">' .
                    setting('PLATFORM_SYSTEM_NAME', 'Admin') .
                    '</span></a>',
            ) !!}

        </h1>
        <div class="navbar-nav flex-row d-lg-none">
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            {!! applyFilters('THEME_SIDEBAR_BEFORE', '') !!}
            {!! menuAdmin(true) !!}
            {!! applyFilters('THEME_SIDEBAR_AFTTER', '') !!}
            <div class=" text-center p-1 text-bold">
                <a href="https://sokeio.com" target="_blank">Sokeio Framework {{ sokeioVersion() }}</a>
            </div>

            <div class="text-center">Page Loaded:{{ sokeioTime() }}ms</div>
        </div>
    </div>
</aside>
