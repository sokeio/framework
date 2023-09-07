@php
    $dataOptions = $item->getDataOption();
    $modelField = $item->getModelField();
@endphp
<div {!! $item->getAttributeContent() !!}>
    @if (!$item->getManager()->IsTable())
        <label class="form-label">{{ $item->getTitle() ?? $item->getField() }}</label>
    @endif
    @if ($dataOptions)
        <div class="row">
            @foreach ($dataOptions as $item)
                <div class="col-6">
                    <label class="form-check form-switch">
                        <input wire:model='{{ $modelField }}' name="{{ $modelField }}" class="form-check-input"
                            type="checkbox" value="{{ $item['value'] }}">
                        <span class="form-check-label">{{ $item['text'] }}</span>
                    </label>
                </div>
            @endforeach
        </div>
    @endif
    @error($modelField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>
