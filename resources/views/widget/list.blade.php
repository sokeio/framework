<div class="row row-deck row-cards g-2"
    {{-- @if (!$locked) wire:sortable="updateWidgetOrder" wire:sortable.options="{ animation: 100 }" @endif> --}}
    @foreach ($widgets as $widgetId => $widget)
        @if ($widget->isActive())
            <livewire:sokeio::widget :$widgetId :$locked wire:key='widget-{{ $widgetId }}' />
        @endif
    @endforeach
</div>
