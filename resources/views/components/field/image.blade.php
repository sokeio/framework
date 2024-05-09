@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $mutil = $column->getMutil();
@endphp
<button x-show="!fieldValue" style="display: none;" name="field-{{ $modelField }}" {!! $column->getAttribute() ?? '' !!}
    sokeio:filemanager="{{ $formField }}" @if ($mutil) sokeio:filemanager-mutil @endif
    class="dropzone dz-clickable">
    <span class="dz-default dz-message">@lang('Choose Images')</span>
</button>
<div x-show="fieldValue" class="border border-1 text-end">
    <button x-show="fieldValue" @click="fieldValue = ''" class="btn btn-warning btn-sm m-1">{{ __('Clear') }}</button>

    <div sokeio:filemanager="{{ $formField }}" @if ($mutil) sokeio:filemanager-mutil @endif>
        @if ($mutil)
            <template x-for="item in fieldValue">
                <img :src="item" alt="{{ $modelLabel }}" />
            </template>
        @else
            <img :src="fieldValue" alt="{{ $modelLabel }}" />
        @endif
    </div>
</div>
