{{-- @php
    $column->LevelIndex();
@endphp --}}
{{-- @foreach ($column->getArrayData() as $key => $item)
    @php
        $column->LevelData($item, 'EachData');
        $column->LevelData($key, 'EachKey');
        $column->LevelData($loop->index, 'EachIndex');
    @endphp --}}
    @includeIf('sokeio::components.layout', [
        'layout' => $column->getChildContentEach(),
        'dataItem' => $column->getDataItem(),
    ])
{{-- @endforeach --}}
