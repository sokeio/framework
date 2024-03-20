<div class="d-flex p-1 {{ $item['color'] ?? '' }}">
    @if ($item['icon'])
        <i class="{{ $item['icon'] }} fs-3 me-2"></i>
    @endif
    <div class="flex-grow-1 justify-content-center align-items-center pe-5" data-menu="{{ $item['id'] }}">
        {{ $item['name'] }}
        @if ($item['info'])
            <div class="bg-warning p-2 rounded">{{ $item['info'] }}</div>
        @endif
    </div>
    <button sokeio:modal="{{ route('admin.menu-item-form', ['dataId' => $item['id']]) }}"
        sokeio:modal-title="Menu Item:{{ $item['name'] }}" class="flex-shrink-0 btn btn-sm btn-success"><i
            class="ti ti-edit"></i></button>
</div>
