@php
    $dataOptions = $item->getDataOption();
    $modelField = $item->getModelField();
@endphp
<div {!! $item->getAttributeContent() !!}>
    @if (!$item->getManager()->IsTable())
        <label class="form-label">{{ $item->getTitle() ?? $item->getField() }}</label>
    @endif
    <div wire:ignore>
        <input wire:tagify wire:tagify.options='@json($item->getFieldOption())' {!! $item->getAttribute() ?? '' !!} class="form-control"
            wire:model='{{ $modelField }}' name="{{ $modelField }}" placeholder="{{ $item->getPlaceholder() }}">
    </div>
    @error($modelField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>
