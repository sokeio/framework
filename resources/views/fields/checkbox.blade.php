@php
    $dataOptions = $item->getDataOption();
    $modelField = $item->getModelField();
@endphp
<div {!! $item->getAttributeContent() !!}>
    @if (!$item->getManager()->IsTable())
        <label class="form-label">{{ $item->getTitle() ?? $item->getField() }}</label>
    @endif
    <label class="form-check">
        <input {!! $item->getAttribute() ?? '' !!} wire:model='{{ $modelField }}' name="{{ $modelField }}" class="form-check-input"
            type="checkbox" value="{{ $item->getFieldValue() ?? 1 }}">
        <span class="form-check-label">{{ $item->getTitle() ?? $item->getField() }}</span>
    </label>
    @error($modelField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>
