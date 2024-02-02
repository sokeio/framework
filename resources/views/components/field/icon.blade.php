@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
@endphp
<div>
    <p x-show="$wire.{{ $formField }}">
        <span x-bind:class="$wire.{{ $formField }}"></span>
        <span class="form-text" x-text="$wire.{{ $formField }}"></span><button class=" ms-3 btn btn-danger btn-sm"
            @click="$wire.{{ $formField }}=''">Remove</button>
    </p>
    <button class="btn btn-sm btn-primary" sokeio:form-setting="sokeio_icon_setting"
        sokeio:form-setting.model="{{ $formField }}">Change Icon</button>
</div>
