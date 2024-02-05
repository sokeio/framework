<div class="d-flex p-1 {{ $item['color'] ?? '' }}">
    <div class="flex-grow-1 justify-content-center align-items-center" data-menu="{{ $item['id'] }}">
        @if ($item['icon'])
            <i class="{{ $item['icon'] }} fs-3"></i>
        @endif {{ $item['name'] }}
    </div>
    <button sokeio:modal="{{ route('admin.menu-item-form', ['dataId' => $item['id']]) }}"
        sokeio:modal-title="Menu Item:{{ $item['name'] }}" class="flex-shrink-0 btn btn-sm btn-success"><i
            class="ti ti-edit"></i></button>
</div>
