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
        data_set($arrayTree, $valueKey . '._treeview_items', [
            'label' => data_get($item, $FieldText),
            'id' => data_get($item, $FieldKey),
        ]);
        // $keyTree = '';
        // $arrKeys = explode('.', $valueKey);
        // $levelIndex = 0;
        // foreach ($arrKeys as $value) {
        //     $keyTree .= $keyTree ? '.' . $value : $value;

        //     if ($valueKey == $keyTree) {
        //         $arrayTree[$keyTree] = [
        //             'label' => data_get($item, $FieldText),
        //             'id' => data_get($item, $FieldKey),
        //         ];
        //     } else {
        //         $arrayTree[$keyTree] = false;
        //     }
        // if (!isset($arrayTree['level' . $levelIndex])) {
        //     $arrayTree['level' . $levelIndex] = [];
        // }
        // $arrayTree['level' . $levelIndex][] = $keyTree;
        //     $levelIndex++;
        // }
    }
@endphp
<div>
    @if ($datasources)
        @include('sokeio::components.field.tree-view-item', [
            'arrayTree' => $arrayTree,
            'formField' => $formField,
        ])
    @endif
</div>
