<div x-data="sokeioPermissionList()" class="permission-container">
    @foreach ($allPermissions as $key => $item)
        <div class="permission-card">
            <div class="permission-card-header permission-group">
                <input class="form-check-input m-0" type="checkbox" id="permission-card-{{ $key }}"
                    @change="changeCheck(event)" />
                <span class="form-check-label" for="permission-card-{{ $key }}">
                    {{ $item['info']['name'] }}
                </span>
            </div>
            <div class="permission-card-body">
                @php
                    $children = $item['children'];
                    $children = $item['children']->where(function ($child) {
                        return count($child['children']) === 0;
                    });
                @endphp
                @if (count($children) > 0)
                    <div class="permission-box">
                        <div class="permission-box-header permission-group">
                            <input class="form-check-input m-0" type="checkbox"
                                id="permission-box-{{ $key }}-{{ $item['level'] }}"
                                @change="changeCheck(event)" />
                            <span class="form-check-label"
                                for="permission-box-{{ $key }}-{{ $item['level'] }}">
                                {{ $item['info']['name'] }}
                            </span>
                        </div>
                        <div class="permission-box-body">
                            @foreach ($children as $child)
                                <div class="permission-item">
                                    <input class="form-check-input m-0" type="checkbox" wire:model="values"
                                        value="{{ $child['info']['id'] }}">
                                    <span class="form-check-label">{{ $child['info']['name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @foreach ($item['children'] as $child)
                    @if (count($child['children']) > 0)
                        <div class="permission-box">
                            <div class="permission-box-header permission-group">
                                <input class="form-check-input m-0" type="checkbox"
                                    id="permission-box-{{ $key }}-{{ $child['level'] }}"
                                    @change="changeCheck(event)" />
                                <span class="form-check-label"
                                    for="permission-box-{{ $key }}-{{ $child['level'] }}">
                                    {{ $child['info']['name'] }}
                                </span>
                            </div>
                            <div class="permission-box-body">
                                @include('sokeio::livewire.permission-list.tree', [
                                    'items' => $child['children'],
                                    'level' => $child['level'] + 1,
                                ])
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>
