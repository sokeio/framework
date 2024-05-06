@php
    $modelLabel = $column->getLabel() ?? $column->getName();
    $formField = $column->getFormField();
    $infoText = $column->getInfoText();
    $uiBefore = $column->getUIBefore();
    $uiAfter = $column->getUIAfter();
    $afterTemplate = $column->getAfterTemplate();
    $hideLabel = $column->getHideLabel();
    $enableEditInTable = $column->getEnableEditInTable();
    $collapse = $column->getCollapse();
    $isColumnClass = $column->checkColumnClass();
    $classFieldDiv = $column->getClassName() ?? 'mb-3';
    if ($enableEditInTable) {
        $classFieldDiv = '';
    }
@endphp
<div x-data="{ showTemplate: false, showCollapse: {{ $collapse === true ? 'true' : 'false' }} }" @if ($isColumnClass) class="{{ $column->getColumnClass() }}" @endif>
    <div class="{{ $classFieldDiv }}" {!! $column->getAttribute() ?? '' !!}>

        @if (!$hideLabel)
            <label class="form-label position-relative" {!! $column->getAttributeLabel() ?? '' !!}>
                {!! $modelLabel !!}
                @if ($collapse !== null)
                    <span title="Toggle" class="position-absolute top-0 end-0 badge bg-primary"
                        x-on:click="showCollapse = !showCollapse">
                        <i class="ti ti-chevrons-down fs-2 text-bg-primary" x-show="showCollapse"></i>
                        <i class="ti ti-chevrons-up fs-2 text-bg-primary" x-show="!showCollapse"></i>
                    </span>
                @endif
            </label>
        @endif
        <div class="field-body-wrapper" x-show="!showCollapse">
            @if ($uiBefore || $uiAfter)
                <div class="input-group">
            @endif
            @if ($uiBefore)
                @include('sokeio::components.layout', ['layout' => $uiBefore])
            @endif
            @include($column->getFieldView(), ['column' => $column])
            @if ($uiAfter)
                @include('sokeio::components.layout', ['layout' => $uiAfter])
            @endif
            @if ($uiAfter || $uiBefore)
        </div>
        @endif
        @error($formField)
            <div>
                <span class="error">{{ $message }}</span>
            </div>
        @enderror
        @if ($infoText)
            <small class="info-text">{{ $infoText }}</small>
        @endif
        @if ($afterTemplate)
            <template x-if="showTemplate" x-data="{
                formField: '{{ $formField }}',
                setValue: function(value) {
                    $wire.set(this.formField, value);
                    this.showTemplate = false;
                }
            }">
                @include('sokeio::components.layout', ['layout' => $afterTemplate])
            </template>
        @endif
    </div>
</div>
</div>
