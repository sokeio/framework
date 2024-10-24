<div class="p-3">
    <div class="row menu-parent-manager" x-data="{
        async addMenuItem(data) {
                return await this.$wire.doAddMenu(data);
            },
    
    }">
        <div class="{{ columnSize('col4') }}">
            <div class="accordion bg-white" id="accordion-menu">
                @foreach (applyFilters('SOKEIO_MENU_ITEM_MANAGER', []) as $item)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="menu-header-{{ $item['key'] }}">
                            <button class="accordion-button @if ($loop->index > 0) collapsed @endif"
                                type="button" data-bs-toggle="collapse" data-bs-target="#menu-{{ $item['key'] }}"
                                aria-expanded="@if ($loop->index == 0) true @else false @endif "
                                wire:ignore.self>
                                {!! $item['title'] !!}
                            </button>
                        </h2>
                        <div id="menu-{{ $item['key'] }}"
                            class="accordion-collapse collapse @if ($loop->index == 0) show @endif"
                            data-bs-parent="#accordion-menu" wire:ignore.self>
                            <div class="accordion-body pt-0">
                                {!! $item['body'] !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="{{ columnSize('col8') }}">
            <div class="card" wire:key='locationId-{{ $locationId }}-{{ $soNumberLoading }}'>
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

                <template x-if="$wire.locationId>0">
                    <div>
                        <div wire:loading wire:target="locationId" class="p-2">
                            Loading...
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
                                    'menuLists' => $menuLists,
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
                                        <input wire:model.live='menuLocations.{{ $item }}'
                                            wire:loading.attr="disabled" class="form-check-input" value="false"
                                            type="checkbox" />
                                        <span class="form-check-label">{{ $item }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer p-2">
                            <div class="row">
                                <div class="col-auto"><button class="btn btn-danger" wire:click='doDeleteMenu()'
                                        sokeio:confirm="Are you deleting this menu sure?"
                                        wire:loading.attr="disabled">Delete</button></div>
                                <div class="col"></div>
                                <div class="col-auto"><button class="btn btn-blue" sokeio:modal-title="Edit Menu"
                                        sokeio:modal="{{ route('admin.menu-form', ['dataId' => $locationId]) }}"
                                        wire:loading.attr="disabled">Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>
</div>
