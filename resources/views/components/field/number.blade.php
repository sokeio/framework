@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $digits = $column->getDigits();
    $fromField = trim($column->getFromField());
    $toField = trim($column->getToField());

    $MinValue = $column->getMinValue();
    $MaxValue = $column->getMaxValue();
@endphp
<input x-mask:dynamic="$money($input, '.', ',', {{ $digits }})" type="text" class="form-control"
    name="field-{{ $modelField }}" placeholder="{{ $modelPlaceholder }}" {!! $column->getWireAttribute() !!} x-data="{
        flg: false
    }"
    x-init="@if ($toField != null && $toField != '') $watch('$wire.{{ $toField }}',
function() {
    if (flg) return;
    flg = true;
    if ($wire.{{ $formField }} > $wire.{{ $toField }}) {
        $wire.{{ $formField }} = $wire.{{ $toField }};
    }
    flg = false;
}); @endif
    @if ($fromField != null && $fromField != '') $watch('$wire.{{ $fromField }}',
function() {
    if (flg) return;
    flg = true;
    if ($wire.{{ $fromField }} > $wire.{{ $formField }}) {
        $wire.{{ $formField }} = $wire.{{ $fromField }};
    }
    flg = false;
}); @endif">
