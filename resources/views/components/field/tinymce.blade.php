@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
@endphp
<div wire:ignore>
    <textarea class="form-control" name="field-{{ $modelField }}" placeholder="{{ $modelPlaceholder }}"
        wire:tinymce='@json($column->getFieldOption())' wire:tinymce-model="{{ $modelField }}" {!! $column->getWireAttribute() !!}></textarea>
</div>