<div x-data="{
    changeCheck(event) {
            let isChecked = event.target.checked;
            event.target.parentElement.parentElement.querySelectorAll('input').forEach(item => {
                if (item === event.target) {
                    return;
                }
                item.checked = isChecked;
                item.dispatchEvent(new Event('change'))
            });
            setTimeout(() => {
                this.checkGroupPermission();
            }, 100);
        },
        checkGroupPermission() {
            this.$el.querySelectorAll('.permission-group input').forEach(item => {
                let elPermissions = [...item.parentElement
                    .parentElement.querySelectorAll('.permission-item input')
                ].filter(permission => permission != item);
                if (elPermissions.length > 0) {
                    item.checked = elPermissions.every(permission => permission.checked);
                } else {
                    item.checked = true;
                }
            })
        },
        init() {
            this.$el.querySelectorAll('.permission-item input').forEach(item => {
                item.addEventListener('change', this.checkGroupPermission.bind(this));
            });
            setTimeout(() => {
                this.checkGroupPermission();
            })
        }
}" class="permission-container">
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
