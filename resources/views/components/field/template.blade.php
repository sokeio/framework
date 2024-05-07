@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
@endphp
<div wire:ignore x-data="{
    set dataFields(val) {
        $wire.{{ $formField }} = val;
    },
    get dataFields() {
        return $wire.{{ $formField }};
    }
}">
    @includeIf($column->getTemplateView(), [
        'column' => $column,
        'formField' => $formField,
        'modelField' => $modelField,
        'modelLabel' => $modelLabel,
        'modelPlaceholder' => $modelPlaceholder,
    ])
</div>
