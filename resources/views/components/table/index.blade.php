<div class="card" x-data="{

    tableFilter: {{ $showSearchlayout ? 'true' : 'false' }},
    tableLoading() {
        if ($wire.lazyloadingTable == true) {
            $wire.lazyloadingTable = false;
            $wire.doSearch();
        }
        this.checkSelectAll();
        let self = this;
        Livewire.hook('request', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                setTimeout(() => {
                    self.checkSelectAll();
                }, 0);
            })
        })
    },
    selectAll: false,
    checkSelectAll() {
        let checkIdsOnPage = [...$el.querySelectorAll('.selectable')].map((el) => {
            return el.value;
        });
        let chooseIdsOnPage = this.$wire.selectIds.filter((item) => checkIdsOnPage.includes(item));
        this.selectAll = chooseIdsOnPage.length > 0 && chooseIdsOnPage.length == checkIdsOnPage.length;
    },
    toggleAllCheckboxes() {
        this.selectAll = !this.selectAll;
        let checkIdsOnPage = [...$el.querySelectorAll('.selectable')].map((el) => {
            return el.value;
        });
        if (this.selectAll) {
            this.$wire.selectIds = [...this.$wire.selectIds, ...checkIdsOnPage];
        } else {
            this.$wire.selectIds = this.$wire.selectIds.filter((item) => !checkIdsOnPage.includes(item));
        }
    }
}" x-init="tableLoading">
    <div class="card-body border-bottom py-3" x-init="">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <div class="input-icon">
                    <div class="input-group">
                        @if ($searchWithColumns && count($searchWithColumns) > 0)
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                                Search
                            </button>
                            <div class="dropdown-menu" style="">
                                @foreach ($searchWithColumns as $field)
                                    @if (!$field->getWithoutSearch())
                                        <div class="dropdown-item p-0 m-0">
                                            <label class="form-check  p-2 ps-6 m-0">
                                                <input class="form-check-input" type="checkbox"
                                                    wire:model='searchWithFields' value="{{ $field->getName() }}">
                                                <span class="form-check-label"> {{ $field->getLabel() }}</span>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <input type="text" wire:model='textSearch' wire:keydown.enter="doSearch()"
                            wire:blur="doSearch()" class="form-control" placeholder="Search…">
                    </div>
                    <span class="input-icon-addon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                            <path d="M21 21l-6 -6"></path>
                        </svg>
                    </span>
                </div>
            </div>
            @isset($searchlayout)
                @if (count($searchlayout) > 0)
                    <div class="col-auto">
                        <a class="btn btn-icon" aria-label="Button" @click="tableFilter=!tableFilter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter-search"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M11.36 20.213l-2.36 .787v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414" />
                                <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M20.2 20.2l1.8 1.8" />
                            </svg>
                        </a>
                    </div>
                @endif
            @endisset
            <div class="col">
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    @includeIf('sokeio::components.layout', ['layout' => $buttons])
                </div>
            </div>
        </div>

    </div>
    @isset($searchlayout)
        @if (count($searchlayout) > 0)
            <div class="border-bottom py-3 px-3" @if (!$showSearchlayout) style="display: none" @endif
                :style="tableFilter ? { display: 'block' } : { display: 'none' }">
                @includeIf('sokeio::components.layout', ['layout' => $searchlayout])
                <a class="btn btn-primary" aria-label="Button" wire:click="doSearch()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>
                    @lang('Search')
                </a>
            </div>
        @endif
    @endisset
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox"
                            @click="toggleAllCheckboxes()" type="checkbox" x-bind:checked="selectAll"
                            autocomplete="off" aria-label="Select all"></th>
                    @isset($tablecolumns)
                        @foreach ($tablecolumns as $column)
                            <th data-field="{{ $column->getName() }}"
                                @if ($columnWidth = $column->getColumnWidth()) style="width: {{ $columnWidth }}" @endif>
                                @if ($column->getNoSort())
                                    {{ $column->getLabel() }}
                                @else
                                    <button
                                        class="table-sort
                                    @isset($orderBy->{$column->getName()})
                                    @if ($orderBy->{$column->getName()})
                                    desc
                                    @else
                                    asc
                                    @endif
                                    @endisset
                            "
                                        wire:click="doSort('{{ $column->getName() }}')"> {{ $column->getLabel() }} </button>
                                @endif
                            </th>
                        @endforeach
                    @endisset
                    @if (count($tableActions))
                        <th>
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if ($datatable)
                    @foreach ($datatable as $row)
                        <tr>
                            <td><input wire:model='selectIds' class="form-check-input m-0 align-middle selectable"
                                    type="checkbox" value="{{ $row->id }}" wire:key='checkbox-{{ $row->id }}'>
                            </td>
                            @isset($tablecolumns)
                                @foreach ($tablecolumns as $column)
                                    @php
                                        $column->clearCache();
                                        $column->dataItem($row);
                                    @endphp
                                    <td @if ($cellClass = $column->getCellClass()) class="{{ $cellClass }}" @endif>
                                        {!! $column->columnValueRender() !!}
                                    </td>
                                @endforeach
                            @endisset

                            @if (count($tableActions))
                                <td class="text-end">
                                    @includeIf('sokeio::components.layout', [
                                        'layout' => $tableActions,
                                        'dataItem' => $row,
                                    ])
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">

        <div class="text-secondary me-1">
            Show
            <div class="mx-2 d-inline-block">
                <select class="form-select" wire:model.live='pageSize'>
                    @isset($pageSizes)
                        @foreach ($pageSizes as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            entries
        </div>
        <div class="m-0 ms-auto">
            @if ($datatable)
                {{ $datatable->links('sokeio::pagination') }}
            @endif
        </div>
    </div>
</div>
