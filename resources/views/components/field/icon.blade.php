@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
@endphp
<div>
    <div class="d-flex">
        <button class="btn btn-sm btn-primary" sokeio:form-setting="sokeio_icon_setting"
            sokeio:form-setting.model="{{ $formField }}">Change Icon</button>
        <button x-show="$wire.{{ $formField }}" style="display: none"  class=" ms-3 btn btn-danger btn-sm" @click="$wire.{{ $formField }}=''">Remove</button>
    </div>
    <div class="mt-2" x-show="$wire.{{ $formField }}" style="display: none">
        <p class="form-text" x-text="$wire.{{ $formField }}"></p>
        <span style="font-size: 3rem" x-bind:class="$wire.{{ $formField }}"
            :title="$wire.{{ $formField }}"></span>
    </div>
</div>
