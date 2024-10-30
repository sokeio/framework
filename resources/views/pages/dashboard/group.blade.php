<div class="row row-cards sokeio-widget-group-{{ $group }}  mb-4">
    @foreach ($widgets as $item)
        @if ($item['group'] == $group)
            <livewire:sokeio::widget-component wire:key="widget-{{ $item['id'] }}" widgetId="{{ $item['id'] }}"
                dashboardKey="{{ $dashboardKey }}" />
        @endif
    @endforeach
</div>