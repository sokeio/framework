@php
    $dataOptions = $item->getDataOption();
    $modelField = $item->getModelField();
@endphp
<div {!! $item->getAttributeContent() !!}>
    @if (!$item->getManager()->IsTable())
        <label class="form-label">{{ $item->getTitle() ?? $item->getField() }}</label>
    @endif
    <textarea {!! $item->getAttribute() ?? '' !!} class="form-control" wire:model='{{ $modelField }}' name="{{ $modelField }}"
        placeholder="{{ $item->getPlaceholder() }}"></textarea>
    @error($modelField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>
