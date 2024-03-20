@php
    $modelLabel = $column->getLabel() ?? $column->getName();
    $formField = $column->getFormField();
    $infoText = $column->getInfoText();
    $uiBefore = $column->getUIBefore();
    $uiAfter = $column->getUIAfter();
@endphp
<div class=" {{ $column->getClassName() ?? 'mb-3' }}" {!! $column->getAttribute() ?? '' !!}>
    <label class="form-label" {!! $column->getAttributeLabel() ?? '' !!}>{!! $modelLabel !!}</label>
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
</div>
