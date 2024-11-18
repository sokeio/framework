@foreach ($items as $item)
    @if (count($item['children']) === 0)
        <div class="permission-item" tree-level="{{ $item['level'] }}">
            <input class="form-check-input m-0" type="checkbox" value="{{ $item['info']['slug'] }}">
            <span class="form-check-label">{{ $item['info']['name'] }}</span>
        </div>
    @endif
@endforeach
@foreach ($items as $item)
    @if (count($item['children']) > 0)
        <div class="permission-tree">
            <div class="permission-tree-header permission-group">
                <input class="form-check-input m-0" type="checkbox" value="{{ $item['info']['slug'] }}"
                    @change="changeCheck(event)">
                <span class="form-check-label">{{ $item['info']['name'] }}</span>
            </div>
            <div class="permission-tree-body">
                @include('sokeio::livewire.permission-list.tree', [
                    'items' => $item['children'],
                    'level' => $item['level'] + 1,
                ])
            </div>
        </div>
    @endif
@endforeach
