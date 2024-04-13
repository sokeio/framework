<aside class="navbar navbar-vertical navbar-expand-lg navbar-transparent border-end">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            @includeIf(applyFilters('THEME_ADMIN_LOGO', 'theme::partials.logo'))
        </h1>
        <div class="navbar-nav flex-row d-lg-none">
        </div>
        <div class="collapse navbar-collapse sidebar-menu" id="sidebar-menu">
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
