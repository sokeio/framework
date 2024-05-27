@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $modalChoose = $column->getModalChoose();
    $templateView = $column->getTemplate();
@endphp
<button {!! $item->getAttribute() ?? '' !!} sokeio:modal-choose="{{ $modalChoose ?? '' }}" {!! $column->getSokeAttribute() ?? '' !!}
    class="btn btn-blue btn-sm">
    <span class="dz-default dz-message">@lang('Choose :label', ['label' => $modelLabel]) </span>
</button>
@if (!$column->getHideData())
    <div wire:ignore x-show="$wire.{{ $formField }}" x-data="{
        dataItemIds: function() {
            return $wire.{{ $formField }};
        }
    }" class="mt-2">
        @if ($templateView)
            {!! $templateView !!}
        @else
            <template x-if="$wire.{{ $formField }}" x-for="itemTextContent in dataItemIds()">
                <label x-show="itemTextContent" class="px-2 py-1 me-2 mb-2 border" x-text="itemTextContent"></label>
            </template>
        @endif
    </div>
@endif
