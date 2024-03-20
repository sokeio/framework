@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();

    $modalChoose = $column->getModalChoose();
    $modalTitle = $column->getModalTitle();
    $modalSize = $column->getModalSize();
    $modal = $column->getModal();
    $templateView = $column->getTemplate();
@endphp
<button {!! $item->getAttribute() ?? '' !!} sokeio:modal-choose="{{ $modalChoose ?? '' }}" sokeio:modal="{{ $modal ?? '' }}"
    sokeio:modal-title="{{ $modalTitle ?? '' }}" sokeio:modal-size="{{ $modalSize ?? '' }}"
    sokeio:model="{{ $formField }}" class="btn btn-blue btn-sm">
    <span class="dz-default dz-message">Choose {{ $modelLabel }}</span>

</button>
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
