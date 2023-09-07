<div class="d-flex position-relative" x-data="{ showFilter: false }">
    @if ($column->getSort() && $manager->getSort())
        @php
            $columnSort = '';
            $sortType = $sorts->{$column->getField()};
            
            if ($sortType === 0) {
                $columnSort = ':class="\'asc\'"';
            }
            if ($sortType === 1) {
                $columnSort = ':class="\'desc\'"';
            }
        @endphp
        <button {!! $columnSort !!} wire:click='doSort("{{ $column->getField() }}")' class="table-sort me-auto"
            data-sort="sort-{{ $column->getField() }}">
            {{ $column->getTitle() ?? $column->getField() }}
        </button>
    @else
        <div class="me-auto">
            {{ $column->getTitle() ?? $column->getField() }}
        </div>
    @endif
    @if ($column->getFilter() && $manager->getFilter())
        <button @click="showFilter=!showFilter" class="btn btn-sm flex-none btn-icon" aria-label="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-filter" width="24"
                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path
                    d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z">
                </path>
            </svg>
        </button>
        <div x-show="showFilter" class="position-absolute w-100 text-end" style="display:none;">
            <div @click.outside="showFilter = false"
                class="mt-4 ml-auto border-1 border-azure bg-primary rounded-1 w-100 d-inline-block  p-2"
                style=" min-width:250px; max-width:300px;">
                <div x-data="{ text_search: '' }">
                    <input x-model="text_search" type="text" class=" form-control mb-3"
                        placeholder="Search [{{ $column->getTitle() ?? $column->getField() }}]" />
                    <div class="row">
                        <div class="col">
                            <button
                                @click='text_search=""; $wire.doFilter("{{ $column->getField() }}",text_search);showFilter = false;'
                                class=" w-100 btn btn-info ">Cancel</button>
                        </div>
                        <div class="col">
                            <button class=" w-100 btn btn-success "
                                @click=' $wire.doFilter("{{ $column->getField() }}",text_search);showFilter = false;'>Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
