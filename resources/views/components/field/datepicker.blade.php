@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $fromField = $column->getFromField();
    $toField = $column->getToField();
    $xInit = '';
    if ($toField !== null) {
        $toField = $item->getModelField($toField);
        $xInit .= " \$watch('\$wire.{$toField}', function() {
            \$el.\$wire_flatpickr.set('maxDate', \$wire.{$toField});
         });";
    }
    if ($fromField !== null) {
        $fromField = $item->getModelField($fromField);
        $xInit .= " \$watch('\$wire.{$fromField}', function() {
            \$el.\$wire_flatpickr.set('minDate', \$wire.{$fromField});
         });";
    }
    $MinValue = $column->getMinValue();
    $MaxValue = $column->getMaxValue();
    $dateFormat = $column->getFieldOption()['dateFormat'] ?? 'Y-m-d';
@endphp
<div x-data="{ format: '{{ $dateFormat }}'">
    <input wire:ignore name="{{ $modelField }}" wire:flatpickr wire:flatpickr.options='@json($item->getFieldOption())'
        {!! $item->getAttribute() ?? '' !!} class="form-control" wire:model='{{ $formField }}' name="{{ $modelField }}"
        placeholder="{{ $item->getPlaceholder() }}" x-init="{!! $xInit !!}">
</div>
