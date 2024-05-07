<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    @ThemeHead(before)
    @ThemeHead(after)
    @stack('styles')
</head>
{{-- admnin-sidebar-mini --}}

<body class="{{ themeClass() }} {{ \Sokeio\Facades\Theme::isSitebarMini() ? 'admnin-sidebar-mini' : '' }}"
    :data-bs-theme="themeDark && 'dark'" x-data="{initBody(){

        $watch('miniSidebar', (value) => {
            Livewire.dispatch('change-sidebar-admin', {miniSidebar: value});
        })
    }, themeDark: false, miniSidebar: {{ \Sokeio\Facades\Theme::isSitebarMini() ? 'true' : 'false' }} }" :class="{ 'admnin-sidebar-mini': miniSidebar }"
    x-init="initBody"
        >
        @ThemeBody(before) <div class ="page">
    <!-- Sidebar -->
    @include('theme::share.sidebar')
    @include('theme::share.header')
    <div class="page-wrapper">
        @yield('content')
    </div>
    </div>
    @ThemeBody(after)
    @stack('scripts')
    <livewire:sokeio::notifications :showIcon="false">

</body>

</html>
