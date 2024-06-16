@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $skipOption = $column->getSkipOption();
@endphp
<div wire:ignore x-data="{
    async tinymceInit(value) {
        console.log(value);
        let rs = await $refs['tinymce-{{ $formField }}'].$wire_tinymce;
        if (rs && rs[0]) {
            rs[0].setContent(value);
        }
    }
}" x-init="$watch('$wire.{{ $formField }}', (value) => tinymceInit(value))">
    <textarea x-ref="tinymce-{{ $formField }}" class="form-control" name="field-{{ $modelField }}"
        placeholder="{{ $modelPlaceholder }}" wire:tinymce='@json($column->getFieldOption())'
        wire:tinymce-model="{{ $formField }}" {!! $column->getWireAttribute() !!} @if($skipOption) wire:tinymce-skip @endif></textarea>
</div>
