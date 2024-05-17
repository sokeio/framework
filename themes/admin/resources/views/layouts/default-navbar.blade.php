<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    @ThemeHead(before)
    @ThemeHead(after)
    @stack('styles')
    <style type="text/css">
        {!! themeOption('custom_css') !!}
    </style>
</head>

<body class="{{ themeClass() }} theme-layout-navbar admin-page" :data-bs-theme="themeDark && 'dark'" x-data="{ themeDark: false }">
    @ThemeBody(before)
    <div class="page">
        @include('theme::share.header-navbar')
        @php
            doAction('theme::body.before');
        @endphp
        <div class="page-wrapper">
            <!-- Page body -->
            <div class="page-body mt-0 container-fluid">

                {!! breadcrumb()->classBox('pt-3') !!}
                @yield('content')
            </div>
        </div>
        @php
            doAction('theme::body.before');
        @endphp
        @include('theme::share.footer')
    </div>
    @ThemeBody(after)
    @stack('scripts')
    </div>
    <script type="text/javascript">
        {!! themeOption('custom_js') !!}
    </script>
    <livewire:sokeio::notifications :showIcon="false">
</body>

</html>
