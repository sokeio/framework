@php
    $modelLabel = $column->getLabel() ?? $column->getName();
    $formField = $column->getFormField();
    $infoText = $column->getInfoText();
@endphp
<div class=" {{ $column->getClassName() ?? 'mb-3' }}" {!! $column->getAttribute() ?? '' !!}>
    <label class="form-label" {!! $column->getAttributeLabel() ?? '' !!}>{{ $modelLabel }}</label>
    @include($column->getFieldView(), ['column' => $column])
    @error($formField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
    @if ($infoText)
        <small class="info-text">{{ $infoText }}</small>
    @endif
</div>
