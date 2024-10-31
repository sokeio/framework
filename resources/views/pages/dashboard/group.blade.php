<div class="row sokeio-widget-group-{{ $group }}  g-2 mb-4">
    @foreach ($widgets as $item)
        @if ($item['group'] == $group)
            <livewire:sokeio::widget-component wire:key="widget-{{ $item['id'] }}" dashboardKey="{{ $dashboardKey }}"
                :widgetData="$item" :dashboardId="$dashboard_id" />
        @endif
    @endforeach
</div>
