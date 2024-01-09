@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $fromField = $column->getFromField();
    $toField = $column->getToField();

    $MinValue = $column->getMinValue();
    $MaxValue = $column->getMaxValue();
@endphp
<input wire:ignore wire:flatpickr wire:flatpickr.options='@json($item->getFieldOption())' {!! $item->getAttribute() ?? '' !!}
    class="form-control" wire:model='{{ $modelField }}' name="{{ $modelField }}"
    placeholder="{{ $item->getPlaceholder() }}" x-init="@if ($toField) $watch('$wire.{{ $item->getModelField($toField) }}',
    function() {
        $el.livewire____flatpickr.set('maxDate', $wire.{{ $item->getModelField($toField) }});
    }); @endif @if ($fromField) $watch('$wire.{{ $item->getModelField($fromField) }};',
    function() {
        $el.livewire____flatpickr.set('minDate', $wire.{{ $item->getModelField($fromField) }});
    }); @endif">
