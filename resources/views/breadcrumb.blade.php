<div @if ($classBox) class="{{ $classBox }}" @endif>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @isset($breadcrumb)
                @foreach ($breadcrumb as $item)
                    <li class="breadcrumb-item {{ isset($item['class']) ? $item['class'] : '' }}"><a
                            href="{{ isset($item['link']) ? $item['link'] : '' }}" wire:navigate>{{ $item['text'] }}</a></li>
                @endforeach
            @endisset
            <li class="breadcrumb-item active" aria-current="page">{{ isset($title) ? $title : '' }}</li>
        </ol>
    </nav>
</div>
