@php
    $tabActive = 0;
    foreach ($column->getTabs() as $key => $item) {
        if (isset($item['active']) && $item['active'] == true) {
            $tabActive = $key;
            break;
        }
    }
@endphp
<div class="card {{ $column->getClassName() ?? '' }}" {!! $column->getAttribute() ?? '' !!}>
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            @foreach ($column->getTabs() as $key => $item)
                <li class="nav-item" role="presentation">
                    <a href="#tabs-{{ $key }}" class="nav-link @if ($tabActive == $key) active @endif"
                        data-bs-toggle="tab" aria-selected="true" role="tab">
                        {!! $item['icon'] ?? '' !!}
                        {{ $item['title'] ?? '' }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            @foreach ($column->getTabs() as $key => $item)
                <div class="tab-pane @if ($tabActive == $key) active show @endif" id="tabs-{{ $key }}"
                    role="tabpanel">
                    @isset($item['content'])
                        @includeIf('sokeio::components.layout', [
                            'layout' => $item['content'],
                            'dataItem' => $column->getDataItem(),
                        ])
                    @endisset
                </div>
            @endforeach

        </div>
    </div>
</div>
