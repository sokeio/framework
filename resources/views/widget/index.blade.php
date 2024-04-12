@php
    $columnWidget = $widget->getColumn() ?? '';
    $pollWidget = $widget->getPoll() ?? ''; ///wire:poll;
@endphp
<div class="{{ columnSize($columnWidget) }} widget-view" {{ $pollWidget }} wire:sortable.item="{{ $widgetId }}"
    wire:key='widget-{{ $widgetId }}'>
    <div class="card">
        <div class="card-body p-1">
            {{-- @if (!$locked)
                <span wire:sortable.handle class=" position-absolute" style="z-index: 10;top:2px;right:2px">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-move" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 9l3 3l-3 3"></path>
                        <path d="M15 12h6"></path>
                        <path d="M6 9l-3 3l3 3"></path>
                        <path d="M3 12h6"></path>
                        <path d="M9 18l3 3l3 -3"></path>
                        <path d="M12 15v6"></path>
                        <path d="M15 6l-3 -3l-3 3"></path>
                        <path d="M12 3v6"></path>
                    </svg>
                </span>
            @endif --}}
            <div>
                @if ($widget)
                    @includeIf($widget->getView(), $widget->getData())
                @endif
            </div>
        </div>
    </div>
</div>
