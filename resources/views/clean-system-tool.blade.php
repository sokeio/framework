<div class="page-body mt-2">
    <div class="container-fluid">
        <div class="row">
            @foreach ($tools as $tool)
                <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                    <div class="card p-4  mb-3 text-center {{ $tool['card_color'] ?? '' }}">
                        <div class="card-title p-0">{{ $tool['title'] }}</div>
                        <div class="card-body p-0">
                            <div class=" mb-3">
                                {{ $tool['description'] }}
                            </div>
                            <div class="form-group mb-0">
                                <button wire:click='{{ $tool['action'] }}("{{ $tool['message'] }}")'
                                    class="btn {{ $tool['button_color'] ?? 'btn-primary' }}"
                                    wire:loading.attr="disabled" wire:loading.target='{{ $tool['action'] }}'>
                                    @if ($icon = $tool['icon'])
                                        <i class="{{ $icon }} me-2"></i>
                                    @endif <span>{{ $tool['button'] }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
