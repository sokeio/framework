<div class="{{ $column->getClassName() ?? 'container' }}" {!! $column->getAttribute() ?? '' !!}>
    @includeIf('sokeio::components.layout', [
        'layout' => $column->getContent(),
        'dataItem' => $column->getDataItem(),
    ])
</div>
