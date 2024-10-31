<div class="p-3" x-data="{
    widgetKey: '',
    widgetId: '',
    group: 'top',
    groups: ['top', 'center', 'bottom'],
    widgets: [],
    changeGroup(skip = false) {
        this.widgets = $wire.widgets.filter((item) => item.group == this.group);
        if (!skip) {
            this.widgetId = '';
        }
    },
    updateSortable(items) {
        let widgetTemps = $wire.widgets.filter((item) => item.group != this.group);
        const widgets = [...widgetTemps, ...items.map((item) => $wire.widgets.find((w) => w.id == item.value))];
        $wire.widgets = widgets;
    },
    async chooseWidget(widgetId) {
        this.widgetId = widgetId;
        await $wire.chooseWidget(widgetId);
    },
    async addWidget() {
        if (this.widgetKey) {
            await $wire.addWidget(this.widgetKey, this.group, 'column3', []);
            this.changeGroup(true);
        }
    }
}" x-init="changeGroup();
$watch('group', value => changeGroup());">
    <div class="mb-3">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-4">
                <div class="mb-1">
                    <label class="form-label">Name</label>
                    <input wire:model='name' type="text" class="form-control" placeholder="Enter dashboard name">
                </div>
            </div>
            <div class="col-12 col-md-2 col-lg-2">
                <div class="mb-1">
                    <div class="form-label">Default</div>
                    <label class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" wire:model="isDefault">
                        <span x-show="$wire.isDefault" class="form-check-label">Active</span>
                        <span x-show="!$wire.isDefault" class="form-check-label">UnActive</span>
                    </label>
                </div>
            </div>
            <div class="col-12 col-md-2 col-lg-2">
                <div class="mb-1">
                    <div class="form-label">Status</div>
                    <label class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" wire:model="isActive">
                        <span x-show="$wire.isActive" class="form-check-label">Active</span>
                        <span x-show="!$wire.isActive" class="form-check-label">UnActive</span>
                    </label>
                </div>
            </div>
            <div class="col-12 col-md-2 col-lg-2">
                <div class="mb-1">
                    <div class="form-label">Private</div>
                    <label class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" wire:model="isPrivate">
                        <span x-show="$wire.isPrivate" class="form-check-label">Active</span>
                        <span x-show="!$wire.isPrivate" class="form-check-label">UnActive</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="mb-1 mt-1">
            <label class="form-label">Description <span class="form-label-description"
                    x-text="$wire.description.length"></span></label>
            <textarea wire:model="description" class="form-control" rows="3" placeholder="Enter description">
            </textarea>
        </div>
        <button class="btn btn-primary" type="button" wire:click="save">
            <i class="ti ti-device-floppy"></i> <span>Save</span>
        </button>
        <button class="btn btn-danger" type="button" wire:click="remove" wire:confirm='Are you sure want to remove?'>
            <i class="ti ti-close"></i> <span>Remove</span>
        </button>
    </div>
    <div class="border border-dashed p-2 rounded-1">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    <label class="form-label">Widget Component</label>
                    <button class="btn btn-azure" type="button" :disabled="!widgetKey" @click="addWidget() ">Add
                        Widget</button>
                </div>
                <div>
                    @foreach ($widgetComponents as $widget)
                        <div class="p-1 border mb-1 cursor-pointer"
                            :class="widgetKey == '{{ $widget['key'] }}' ? 'bg-primary text-bg-primary' : ''"
                            @click="widgetKey = '{{ $widget['key'] }}'">
                            {{ $widget['name'] }}</div>
                    @endforeach
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    <label class="form-label">Widget Position</label>
                    <div class="btn-group w-100" role="group">
                        <template x-for="(item, key) in groups">
                            <div>
                                <input type="radio" class="btn-check" :id="`btn-radio-basic-${key}`"
                                    name="btn-radio-basic" x-model="group" :value="item">
                                <label :for="`btn-radio-basic-${key}`" type="button" class="btn"
                                    :class="item == group ? 'btn-primary' : ''" x-text="item"></label>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="" wire:sortable x-data="{
                    onSortable(el, items) {
                        updateSortable([...el]);
                    }
                }">
                    <template x-for="widget in widgets" x-key="widget&&widget['id']">
                        <div wire:sortable.item :data-sortable-id="widget['id']" class="p-1 border mb-1 cursor-pointer"
                            :class="widgetId == widget['id'] ? 'bg-primary text-bg-primary' : ''"
                            @click="chooseWidget(widget['id'])">
                            <div x-text="widget['name']"></div>
                            <div x-text="widget['id']"></div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
                <div class="mb-3" x-show="widgetId">
                    <label class="form-label">Widget Settings</label>
                    <div wire:key='{{ $widgetId }}'>
                        {!! $widgetSettings !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
