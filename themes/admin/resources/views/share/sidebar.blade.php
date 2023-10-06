<aside class="navbar navbar-vertical navbar-expand-lg navbar-transparent border-end">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            {!! apply_filters(
                'THEME_ADMIN_SIDEBAR_LOGO',
                ' <a href="' .
                    route('admin.dashboard') .
                    '">
                                    <span class=" fw-bold fs-1 p-1">Admin System</span>
                                </a>',
            ) !!}

        </h1>
        <div class="navbar-nav flex-row d-lg-none">
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            {!! apply_filters('THEME_SIDEBAR_BEFORE', '') !!}
            {!! menu_render() !!}
            {!! apply_filters('THEME_SIDEBAR_AFTTER', '') !!}
        </div>
    </div>
</aside>
