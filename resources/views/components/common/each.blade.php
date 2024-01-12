@php
    $column->LevelIndex();
@endphp
@foreach ($column->getArrayData() as $item)
    @php
        $column->LevelData($item, 'EachData');
        $column->LevelData($loop->index, 'EachIndex');
    @endphp
    @includeIf('sokeio::components.layout', [
        'layout' => $column->getContent(),
        'dataItem' => $column->getDataItem(),
    ])
@endforeach
