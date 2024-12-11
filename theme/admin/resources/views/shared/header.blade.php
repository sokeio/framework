<div @if (setting('SOKEIO_ADMIN_HEADER_STICKY_ENABLE', true)) class="sticky-top" @endif>
    <header class="navbar navbar-expand-md d-print-none">
        <div class="{{ setting('SOKEIO_ADMIN_HEADER_CONTAINER', 'container-xxl') }}">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="{{ url('/') }}">
                    <img src="{{ platform()->getSystemLogo() }}" class="rounded-2" height="40"
                        alt="{{ platform()->getSystemName() }}">
                </a>
            </div>
            <div class="navbar-nav flex-row order-md-last" x-data="{}">
                <div class="d-none d-md-flex">
                    @themeInclude('shared.header-right')
                </div>
                @auth
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ auth()->user()->name }}</div>
                                <div class="mt-1 small text-secondary">Free</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" data-bs-theme="light">
                            <a href="{{ route('admin.theme-admin-page.account') }}" class="dropdown-item">Profile</a>
                            <div class="dropdown-divider p-0"></div>
                            <a href="{{ route('admin.theme-admin-page.account.setting') }}"
                                class="dropdown-item">Settings</a>
                            <a @click="$dispatch('logout'); setTimeout(() => window.location.reload(), 700);"
                                class="dropdown-item">Logout</a>
                        </div>
                    </div>
                @endauth

            </div>
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                    {!! platform()->menu()->render()  !!}
                </div>
            </div>
        </div>
    </header>
</div>
