<div class="sticky-top ">
    @php
        doAction('theme::share.header.before');
    @endphp
    <header class="navbar navbar-expand-md d-print-none {!! themeOption('header_color') !!}">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="{{ url('/') }}">
                    @if ($logo = themeOption('site_logo'))
                        <img src="{{ $logo }}" width="110" height="32"
                            alt="{{ themeOption('site_name', 'Tools') }}" class="navbar-brand-image">
                    @else
                        <span class="fw-bold fs-3">{{ themeOption('site_name', 'Tools') }}</span>
                    @endif
                </a>
            </h1>
            <div class="navbar-nav flex-row order-md-last">
                @auth
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ auth()->user()->name }}</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" data-bs-theme="light">
                            <a href="{{route('site.logout')}}" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                @else
                    <div class="nav-link">
                        <a href="{{ route('site.login') }}" class="btn bg-red text-red-fg">@lang('Login')</a>
                    </div>
                    {{-- <div class="nav-link">
                        <a href="{{ route('site.sign-up') }}" class="btn btn-primary">@lang('Sign up')</a>
                    </div> --}}
                @endauth
            </div>
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div
                    class="d-flex flex-column dropdown-hover flex-md-row flex-fill align-items-stretch align-items-md-center">
                    {!! menuAdmin(true) !!}
                </div>
            </div>
        </div>
    </header>
    @php
        doAction('theme::share.header.before');
    @endphp
</div>
