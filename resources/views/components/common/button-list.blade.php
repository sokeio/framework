<div class="btn-list {{ $column->getClassName() ?? '' }}" {!! $column->getAttribute() ?? '' !!}>
    @includeIf('sokeio::components.layout', [
        'layout' => $column->getContent(),
        'dataItem' => $column->getDataItem(),
    ])
</div>
