@themeLocation('SOKEIO_ADMIN_THEME_HEADER_RIGHT_BEFORE')
@if (setting('SOKEIO_ADMIN_THEME_MODE_ENABLE', true))
    <a href="#" @click="toggleTheme" class="nav-link px-0 hide-theme-dark" data-bs-toggle="tooltip"
        data-bs-placement="bottom" aria-label="Enable dark mode" data-bs-original-title="Enable dark mode">
        <i class="ti ti-moon fs-2"></i>
    </a>
    <a href="#" @click="toggleTheme" class="nav-link px-0 hide-theme-light" data-bs-toggle="tooltip"
        data-bs-placement="bottom" aria-label="Enable light mode" data-bs-original-title="Enable light mode">
        <i class="ti ti-sun fs-2"></i>
    </a>
@endif
<livewire:sokeio::notification />
@themeLocation('SOKEIO_ADMIN_THEME_HEADER_RIGHT_AFTER')
