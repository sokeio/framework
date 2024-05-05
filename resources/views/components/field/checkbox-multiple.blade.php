@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $datasources = $column->getDataSource();
    $FieldKey = $column->getFieldKey();
    $FieldText = $column->getFieldText();
@endphp
@if ($datasources)
    <div class="row">
        @foreach ($datasources as $item)
            <div class="col-6">
                <div class="form-check pt-2">
                    <input wire:model='{{ $formField }}' name="{{ $formField }}-{{ data_get($item, $FieldKey) }}"
                        id="{{ $formField }}-{{ data_get($item, $FieldKey) }}" class="form-check-input" type="checkbox"
                        value="{{ data_get($item, $FieldKey) }}">
                    <label class="form-check-label" for="{{ $formField }}-{{ data_get($item, $FieldKey) }}">
                        {{ data_get($item, $FieldText) }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
@endif
