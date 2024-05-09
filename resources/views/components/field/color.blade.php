@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
@endphp
<div>
    <div class="d-flex">
        <button class="btn btn-sm btn-primary" sokeio:form-setting="sokeio_color_setting"
            sokeio:form-setting.model="{{ $formField }}">@lang('Change Color')</button>
        <button x-show="fieldValue" style="display: none" class=" ms-3 btn btn-danger btn-sm"
            @click="fieldValue=''">X</button>
    </div>
    <div class="mt-2" x-show="fieldValue" style="display: none">
        <p class="form-text" x-text="fieldValue"></p>
        <span class="p-2" x-bind:class="fieldValue"
            :title="fieldValue">{!! $modelPlaceholder !!}</span>
    </div>
</div>
