<div class="{{ $column->getClassName() ?? '' }} {{ columnSize($column->getCol() ?? 'col') }}" {!! $column->getAttribute() ?? '' !!}>
    @includeIf('sokeio::components.layout', [
        'layout' => $column->getContent(),
        'dataItem' => $column->getDataItem(),
    ])
</div>
