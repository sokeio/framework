<div class="p-3">
    <div class="row" wire:key='locationId-{{ $locationId }}' x-data="{
        async addMenuItem(data) {
            return await this.$wire.doAddMenu(data);
        }
    }">
        <div class="{{ column_size('col4') }}">
            <div class="accordion bg-white" id="accordion-menu">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="menu-header-link">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#menu-link" aria-expanded="true" aria-controls="menu-link">
                            Links
                        </button>
                    </h2>
                    <div id="menu-link" class="accordion-collapse collapse show" data-bs-parent="#accordion-menu">
                        <div class="accordion-body pt-0" x-data="{
                            custom_url: '',
                            custom_name: '',
                            custom_class: '',
                            resetData() {
                                this.custom_url = '';
                                this.custom_name = '';
                                this.custom_class = '';
                            },
                            async doAddMenu() {
                                if (await addMenuItem({ link: this.custom_url, name: this.custom_name, class: this.custom_class })) {
                                    this.resetData();
                                }
                            }
                        }">
                            <div class="mb-3">
                                <label class="form-label">Url</label>
                                <input x-model='custom_url' type="text" class="form-control" placeholder="Url">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input x-model='custom_name' type="text" class="form-control" placeholder="Name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Class</label>
                                <input x-model='custom_class' type="text" class="form-control" placeholder="class">
                            </div>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary ms-auto" @click="doAddMenu()">Add
                                    To Menu</button>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach (apply_filters('SOKEIO_MENU_ITEM_MANAGER', []) as $item)
                    <div class="accordion-item" wire:ignore>
                        <h2 class="accordion-header" id="menu-header-{{ $item['key'] }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#menu-{{ $item['key'] }}" aria-expanded="false">
                                {!! $item['title'] !!}
                            </button>
                        </h2>
                        <div id="menu-{{ $item['key'] }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordion-menu">
                            <div class="accordion-body pt-0">
                                {!! $item['body'] !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="{{ column_size('col8') }}">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title ">
                        <div class="row">
                            <div class="col-auto col-form-label">Menu:</div>
                            <div class="col">
                                <select class="form-select" wire:model.live='locationId'>
                                    <option value="0"> None</option>
                                    @foreach ($MenuLocation as $item)
                                        <option value="{{ $item->id }}"> {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </h3>
                    <div class="card-actions">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" sokeio:modal-title="Add Menu"
                            sokeio:modal="{{ route('admin.menu-form') }}" data-bs-target="#addMenuModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M14 4l0 4l-6 0l0 -4"></path>
                            </svg>
                            Add Menu </button>

                    </div>
                </div>
                <div class="sortable-fallback"></div>
                <div class="card-body p-0 position-md-relative">
                    <div wire:sortable-group='doUpdateSortMenu'
                        wire:sortable-group.options='{
                        animation: 150,
                        fallbackOnBody: true,
                        swapThreshold: 0.65,
                        filter: ".ellock",
                        preventOnFilter: false,
                }'
                        wire:sortable-group.item-group="0" class="menu-editor-manager">
                        @includeIf('sokeio::menu.menu-item', [
                            'menu_lists' => $menu_lists,
                            'parent_id' => 0,
                            'level' => 0,
                        ])
                    </div>

                </div>
                <div class="card-footer p-2">
                    <label class="form-label">Location:</label>
                    <div class="" wire:ignore>
                        @foreach ($locations as $item)
                            <label class="form-check">
                                <input wire:model.live='menu_locations.{{ $item }}' class="form-check-input"
                                    value="false" type="checkbox" />
                                <span class="form-check-label">{{ $item }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer p-2">
                    <div class="row">
                        <div class="col-auto"><button class="btn btn-danger" wire:click='doDeleteMenu()'
                                sokeio:confirm="Are you deleting this menu sure?">Delete</button></div>
                        <div class="col"></div>
                        <div class="col-auto"><button class="btn btn-blue" sokeio:modal-title="Edit Menu"
                                sokeio:modal="{{ route('admin.menu-form', ['dataId' => $locationId]) }}">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
