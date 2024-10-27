<div class="row row-cards">
    @if ($themes)
        @foreach ($themes as $theme)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card card-sm">
                    <a class="d-block cursor-pointer sokeio-screenshot-banner" wire:modal
                        wire:modal.url='{{ route($routeName, ['id' => $theme->id]) }}'>
                        <img src="{{ $theme->getScreenshot() }}" class="card-img-top" alt="{{ $theme->name }}">
                    </a>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <div> {{ $theme->name }}</div>
                                <div class="text-secondary">{{ $theme->description }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
