@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
@endphp
<div wire:ignore x-data="{
    async tinymceInit(value) {
        let rs = await $refs['tinymce-{{ $formField }}'].livewire____tinymce;
        if (rs && rs[0]) {
            rs[0].setContent(value);
        }
    }
}" x-init="$watch('$wire.{{ $formField }}', tinymceInit)">
    <textarea x-ref="tinymce-{{ $formField }}" class="form-control" name="field-{{ $modelField }}"
        placeholder="{{ $modelPlaceholder }}" wire:tinymce='@json($column->getFieldOption())'
        wire:tinymce-model="{{ $formField }}" {!! $column->getWireAttribute() !!}></textarea>
</div>
