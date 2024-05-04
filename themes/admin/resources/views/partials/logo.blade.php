<div class="admin-logo">
    <a href="{{ route('admin.dashboard') }}">
        @if ($logo = setting('PLATFORM_SYSTEM_LOGO'))
            <img src="{{ $logo }}" width="110" height="32"
                alt="{{ setting('PLATFORM_SYSTEM_NAME', 'Sokeio.com') }}" class="navbar-brand-image">
        @else
            <span class="fw-bold fs-3">{{ setting('PLATFORM_SYSTEM_NAME', 'Sokeio.com') }}</span>
        @endif
    </a>
</div>
