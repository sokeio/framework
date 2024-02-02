@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
@endphp
<div>
    <span x-show="$wire.{{ $formField }}" x-bind:class="$wire.{{ $formField }}" ></span>
    <div class="form-text" x-text="$wire.{{ $formField }}"></div>
    <button class="btn btn-sm btn-primary" sokeio:form-setting="sokeio_icon_setting"
        sokeio:form-setting.model="{{ $formField }}">Change Icon</button>
</div>
