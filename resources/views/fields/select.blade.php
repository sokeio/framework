@php
    $dataOptions = $item->getDataOption();
    $dataOptionNone = $item->getDataOptionNone();
    $modelField = $item->getModelField();
@endphp
<div {!! $item->getAttributeContent() !!}>
    @if (!$item->getManager()->IsTable())
        <label class="form-label">{{ $item->getTitle() ?? $item->getField() }}</label>
    @endif

    <select {!! $item->getAttribute() ?? '' !!} class="form-select" wire:model='{{ $modelField }}' name="{{ $modelField }}"
        placeholder="{{ $item->getPlaceholder() }}">
        @if ($dataOptionNone)
            <option value="{{ $dataOptionNone['value'] }}">{{ $dataOptionNone['text'] }}</option>
        @endif
        @if ($dataOptions)
            @foreach ($dataOptions as $item)
                <option value="{{ $item['value'] }}">{{ $item['text'] }}</option>
            @endforeach
        @endif
    </select>
    @error($modelField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>
