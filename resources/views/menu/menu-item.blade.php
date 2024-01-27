@php
    $menuItems = collect($menu_lists)
        ->where(function ($item) use ($parent_id) {
            return $item['parent_id'] == $parent_id;
        })
        ->sortBy('order')
        ->toArray();
@endphp
@if (count($menuItems) > 0)
    @foreach ($menuItems as $item)
        <div wire:key='menu-level-{{ $level }}-{{ time() }}{{ $item['id'] }}'
            class="menu-item menu-parent-{{ $parent_id }} menu-level-{{ $level }}"
            wire:sortable-group.item="{{ $item['id'] }}">
            @includeIf('sokeio::menu.menu-content', [
                'menu_lists' => $menu_lists,
                'parent_id' => $parent_id,
                'level' => $level + 1,
                'item' => $item,
            ])
            <div class="menu-group" wire:sortable-group.item-group="{{ $item['id'] }}">
                @includeIf('sokeio::menu.menu-item', [
                    'menu_lists' => $menu_lists,
                    'parent_id' => $item['id'],
                    'level' => $level + 1,
                ])
            </div>
        </div>
    @endforeach
@endif
