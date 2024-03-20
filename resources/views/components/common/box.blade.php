<div class="card {{ $column->getClassName() ?? '' }}" {!! $column->getAttribute() ?? '' !!}>
    <div class="card-body">
        @if ($title = $column->getTitle())
            <h3 class="card-title">{{ $title }}</h3>
        @endif
        @includeIf('sokeio::components.layout', [
            'layout' => $column->getContent(),
            'dataItem' => $column->getDataItem(),
        ])
    </div>
</div>
