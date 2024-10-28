<div class="card card-sm">
    <a class="d-block cursor-pointer sokeio-screenshot-banner sokeio-screenshot-banner-large">
        <img src="{{ $theme->getScreenshot() }}" alt="{{ $theme->name }}">
    </a>
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div>
                <div> {{ $theme->name }}</div>
                <div class="text-secondary">{{ $theme->description }}</div>
            </div>
        </div>
        <div class="mt-3 border-top">
            {!! $theme->getReadme() !!}
        </div>
    </div>
</div>
