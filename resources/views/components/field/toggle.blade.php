@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $modelTitle = $column->getTitle() ?? $modelLabel;
@endphp
<label class="form-check form-switch">
    <input type="checkbox" class="form-check-input" name="field-{{ $modelField }}" placeholder="{{ $modelPlaceholder }}"
        {!! $column->getWireAttribute() !!} />
    <span class="form-check-label">{{ $modelTitle }}</span>
</label>
