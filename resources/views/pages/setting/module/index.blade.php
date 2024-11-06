<div class="row row-cards row-deck">
    @if ($datas)
        @foreach ($datas as $item)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card card-sm">
                    <div class=" position-relative">
                        <a class="d-block cursor-pointer sokeio-screenshot-banner" wire:modal
                            wire:modal.url='{{ route($routeName, ['id' => $item->id]) }}'>
                            <img src="{{ $item->getScreenshot() }}" alt="{{ $item->getTitle() }}">
                        </a>
                        @if ($item->isActive())
                            <span
                                class="position-absolute top-0 start-100 translate-middle p-2 bg-warning border border-light rounded-circle">
                            </span>
                        @endif
                        @if ($item->isVendor())
                            <span
                                class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-2">
                        <div class="d-flex ">
                            <div class="me-auto">{{ $item->getTitle() }} @if ($item->isVendor())(Vendor) @endif</div>
                            <div class="">
                                <span class="badge bg-primary text-bg-primary">{{ $item->getVersion() }}</span>
                            </div>
                        </div>
                        <div class="text-secondary">{!! $item->description !!}</div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
