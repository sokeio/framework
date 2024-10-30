<div dashboard-key="{{ $dashboardKey }}">
    @include('sokeio::pages.dashboard.group', ['group' => 'top', 'widgets' => $widgets])
    @include('sokeio::pages.dashboard.group', ['group' => 'center', 'widgets' => $widgets])
    @include('sokeio::pages.dashboard.group', ['group' => 'bottom', 'widgets' => $widgets])
</div>
