@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $classInput = $column->getClassInput();
@endphp
<textarea class="form-control {{ $classInput }}" name="field-{{ $modelField }}" placeholder="{{ $modelPlaceholder }}"
    {!! $column->getWireAttribute() !!}></textarea>
