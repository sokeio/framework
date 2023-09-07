<div class="row row-deck row-cards g-2"
    @if (!$locked) wire:sortable="updateWidgetOrder" wire:sortable.options="{ animation: 100 }" @endif>
    @foreach ($widgets as $key => $widgetId)
        <livewire:byte::widget :$widgetId :$locked wire:key='widget-{{ $widgetId }}' />
    @endforeach
</div>
