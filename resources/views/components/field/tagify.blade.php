@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
@endphp
<div wire:ignore>
    <input class="form-control" name="field-{{ $modelField }}" placeholder="{{ $modelPlaceholder }}" wire:tagify
        wire:tagify.options='@json($column->getFieldOption())' {!! $column->getWireAttribute() !!} />
</div>
