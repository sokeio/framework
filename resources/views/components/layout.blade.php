@php
    if (is_object($layout)) {
        $layout = [$layout];
    }
@endphp
@if (is_array($layout))
    @foreach ($layout as $item)
        @if ($item && $item->getWhen())
            @isset($dataItem)
                @php
                    $item->ClearCache();
                    $item->dataItem($dataItem);
                @endphp
            @endisset
            @includeIf($item->getView(), ['column' => $item])
        @endif
    @endforeach
@elseif(is_string($layout))
    {!! $layout !!}
@endif
