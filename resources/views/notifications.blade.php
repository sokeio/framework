@foreach ($notifications as $item)
    <div>
        <div class="row">
            <div class="col-auto">
                <span class="avatar">{{ $item->id }}</span>
            </div>
            <div class="col">
                <div class="text-truncate">
                    {{ $item->title }}
                </div>
                <div class="text-secondary">@json($item)</div>
            </div>
            <div class="col-auto align-self-center">
                @if (!count($item->userRead))
                    <div class="badge bg-primary"></div>
                @endif
            </div>
        </div>
    </div>
@endforeach
