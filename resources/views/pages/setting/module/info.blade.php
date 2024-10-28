<div class="card card-sm">
    <a class="d-block cursor-pointer sokeio-screenshot-banner sokeio-screenshot-banner-large">
        <img src="{{ $item->getScreenshot() }}" alt="{{ $item->name }}">
    </a>
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div>
                <div> {{ $item->name }}</div>
                <div class="text-secondary">{{ $item->description }}</div>
            </div>
        </div>
        <div class="mt-3 border-top">
            {!! $item->getReadme() !!}
        </div>
    </div>
</div>
