<div dashboard-key="{{ $dashboard_id }}" wire:key='dashboard-{{ $dashboard_id }}'>
    @json($widgets)
    @include('sokeio::pages.dashboard.group', [
        'group' => 'top',
        'widgets' => $widgets,
        'dashboard_id' => $dashboard_id,
    ])
    @include('sokeio::pages.dashboard.group', [
        'group' => 'center',
        'widgets' => $widgets,
        'dashboard_id' => $dashboard_id,
    ])
    @include('sokeio::pages.dashboard.group', [
        'group' => 'bottom',
        'widgets' => $widgets,
        'dashboard_id' => $dashboard_id,
    ])
</div>
