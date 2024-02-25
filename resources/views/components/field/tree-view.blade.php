@php
    $modelField = $column->getName();
    $modelLabel = $column->getLabel() ?? $modelField;
    $modelPlaceholder = $column->getPlaceholder() ?? $modelLabel;
    $formField = $column->getFormField();
    $datasources = $column->getDataSource();
    $FieldKey = $column->getFieldKey();
    $FieldText = $column->getFieldText();
    $arrayTree = [];
    foreach ($datasources as $item) {
        $valueKey = data_get($item, $FieldText);
        $keyTree = '';
        $arrKeys = explode('.', $valueKey);
        $levelIndex = 0;
        foreach ($arrKeys as $value) {
            $keyTree .= $keyTree ? '.' . $value : $value;

            $arrayTree[$keyTree] = $levelIndex;
            // if (!isset($arrayTree['level' . $levelIndex])) {
            //     $arrayTree['level' . $levelIndex] = [];
            // }
            // $arrayTree['level' . $levelIndex][] = $keyTree;
            $levelIndex++;
        }
    }
@endphp
@if ($datasources)
    <div>
        <pre>
        @php
            print_r($arrayTree);
        @endphp
      </pre>
        {{-- @foreach ($datasources as $item)
            <div class="col-6">
                <label class="form-check">
                    <input wire:model='{{ $formField }}' name="{{ $formField }}" class="form-check-input"
                        type="checkbox" value="{{ data_get($item, $FieldKey) }}">
                    <span class="form-check-label">{{ data_get($item, $FieldText) }}</span>
                </label>
            </div>
        @endforeach --}}
    </div>
@endif
