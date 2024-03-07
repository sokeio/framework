{{-- @php
    $column->levelIndex();
@endphp --}}
{{-- @foreach ($column->getArrayData() as $key => $item)
    @php
        $column->levelData($item, 'EachData');
        $column->levelData($key, 'EachKey');
        $column->levelData($loop->index, 'EachIndex');
    @endphp --}}
    @includeIf('sokeio::components.layout', [
        'layout' => $column->getChildContentEach(),
        'dataItem' => $column->getDataItem(),
    ])
{{-- @endforeach --}}
