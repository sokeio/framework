<div dashboard-key="{{ $dashboardKey }}">
    @foreach ($widgets as $item)
        <livewire:sokeio::widget-component wire:key="widget-{{ $item['id'] }}" widgetId="{{ $item['id'] }}"
            dashboardKey="{{ $dashboardKey }}" />
    @endforeach
</div>
