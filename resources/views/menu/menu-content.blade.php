<div class="menu-content">
    <div class="menu-content_item" data-menu="{{ $item['id'] }}"
        sokeio:modal="{{ route('admin.menu-item-form', ['dataId' => $item['id']]) }}" sokeio:modal-title="Menu Item:{{ $item['name'] }}">
        {{ $item['name'] }}
    </div>
</div>
