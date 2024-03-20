@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
@endphp
<input type="text" class="form-control" name="field-{{ $modelField }}" placeholder="{{ $modelPlaceholder }}"
    {!! $column->getWireAttribute() !!}>
