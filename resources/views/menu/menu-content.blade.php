<div class="d-flex">
    <div class="flex-grow-1" data-menu="{{ $item['id'] }}">
        {{ $item['name'] }}
    </div>
    <button sokeio:modal="{{ route('admin.menu-item-form', ['dataId' => $item['id']]) }}"
        sokeio:modal-title="Menu Item:{{ $item['name'] }}" class="flex-shrink-0 btn btn-sm btn-success"><i class="ti ti-edit"></i></button>
</div>
