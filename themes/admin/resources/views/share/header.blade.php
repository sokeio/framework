<header class="navbar navbar-expand-md navbar-light d-none d-md-flex d-print-none">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-menu">
            {!! breadcrumb() !!}
            @php
                doAction('THEME_ADMIN_LEFT_BEFORE');
            @endphp
            @php
                doAction('THEME_ADMIN_LEFT');
            @endphp
            @php
                doAction('THEME_ADMIN_LEFT_AFTER');
            @endphp
        </div>
        <div class="navbar-nav flex-row order-md-last">
            @php
                doAction('THEME_ADMIN_RIGHT');
            @endphp
            <div class="nav-item d-none">
                <livewire:sokeio::languages />
            </div>
            @includeIf('theme::partials.change-theme')
            @includeIf('theme::partials.notication')
            @includeIf(applyFilters('THEME_ADMIN_USER_PROFILE', 'theme::partials.user-profile'))
        </div>

    </div>
</header>
