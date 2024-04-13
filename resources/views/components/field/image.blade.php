@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $mutil = $column->getMutil();
@endphp
<button x-show="!$wire.{{ $formField }}" style="display: none;" name="field-{{ $modelField }}" {!! $column->getAttribute() ?? '' !!}
    sokeio:filemanager="{{ $formField }}" @if ($mutil) sokeio:filemanager-mutil @endif
    class="dropzone dz-clickable">
    <span class="dz-default dz-message">@lang('Choose Images')</span>
</button>
<div x-show="$wire.{{ $formField }}" class="border border-1 text-end">
    <button x-show="$wire.{{ $formField }}" @click="$wire.{{ $formField }} = ''"
        class="btn btn-warning btn-sm m-1">{{ __('Clear') }}</button>

    <div sokeio:filemanager="{{ $formField }}" @if ($mutil) sokeio:filemanager-mutil @endif>
        @if ($mutil)
            <template x-for="item in $wire.{{ $formField }}">
                <img :src="item" />
            </template>
        @else
            <img :src="$wire.{{ $formField }}" />
        @endif
    </div>
</div>
