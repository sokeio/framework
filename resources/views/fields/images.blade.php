@php
    $dataOptions = $item->getDataOption();
    $modelField = $item->getModelField();
@endphp
<div {!! $item->getAttributeContent() !!}>
    @if (!$item->getManager()->IsTable())
        <label class="form-label">{{ $item->getTitle() ?? $item->getField() }}</label>
    @endif
    <button {!! $item->getAttribute() ?? '' !!} byte:filemanager="{{ $modelField }}"
        @if (isset($dataOptions['mutil'])) byte:filemanager-mutil @endif class="dropzone dz-clickable">
        <span class="dz-default dz-message">Choose Images</span>
        <div x-show="$wire.{{ $modelField }}">
            @if (isset($dataOptions['mutil']))
                <template x-for="item in $wire.{{ $modelField }}">
                    <img :src="item" />
                </template>
            @else
                <img :src="$wire.{{ $modelField }}" />
            @endif

        </div>
    </button>
    @error($modelField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>
