<div class="sticky-top ">
    @php
        doAction('theme::share.header.before');
    @endphp
    <header class="navbar navbar-expand-md d-print-none">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            @includeIf(applyFilters('THEME_ADMIN_LOGO', 'theme::partials.logo'))
            </h1>
            <div class="navbar-nav flex-row order-md-last">
                @includeIf('theme::partials.change-theme')
                @includeIf('theme::partials.notication')
                @includeIf(applyFilters('THEME_ADMIN_USER_PROFILE', 'theme::partials.user-profile'))
            </div>
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div
                    class="d-flex flex-column dropdown-hover flex-md-row flex-fill align-items-stretch align-items-md-center navbar-menu">
                    {!! menuAdmin(true) !!}
                </div>
            </div>
        </div>
    </header>
    @php
        doAction('theme::share.header.before');
    @endphp
</div>
