<div class="{{ $column->getClassName() ?? '' }} {{ column_size($column->getCol() ?? 'col') }}" {!! $column->getAttribute() ?? '' !!}>
    @includeIf('sokeio::components.layout', [
        'layout' => $column->getContent(),
        'dataItem' => $column->getDataItem(),
    ])
</div>
