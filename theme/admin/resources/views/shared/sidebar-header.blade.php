<div @if(setting('SOKEIO_ADMIN_HEADER_STICKY_ENABLE',true)) class="sticky-top" @endif>
    <header class="navbar navbar-expand-md d-print-none">
        <div class="container-fluid">
            <div class="navbar-nav flex-row order-md-last" x-data="{}">
                <div class="d-none d-md-flex">
                    <a href="#" @click="toggleTheme" class="nav-link px-0 hide-theme-dark"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Enable dark mode"
                        data-bs-original-title="Enable dark mode">
                        <i class="ti ti-moon fs-2"></i>
                    </a>
                    <a href="#" @click="toggleTheme" class="nav-link px-0 hide-theme-light"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Enable light mode"
                        data-bs-original-title="Enable light mode">
                        <i class="ti ti-sun fs-2"></i>
                    </a>
                    <livewire:sokeio::notification />
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
                            <a @click="$dispatch('logout'); setTimeout(() => window.location.reload(), 1000);"
                                class="dropdown-item">Logout</a>
                        </div>
                    </div>
                @endauth

            </div>
            <div class="collapse navbar-collapse" id="navbar-menu">
            </div>
        </div>
    </header>
</div>
