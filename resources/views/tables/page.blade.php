@php
    $page_title = $itemManager->getTitle();
    $formSearchId = 'formSearch-' . time();
    $formSearchManger = $itemManager->getFormSearch();
@endphp
<div class="table-page">
    <div class="page-header d-print-none">
        <div class="container-fluid">
            @if ($formSearchManger)
                <div class="accordion" wire:ignore id="{{ $formSearchId }}-parent">
                    <div class="accordion-item">
                        <h2 class="accordion-header " id="{{ $formSearchId }}-header">
                            <button class="accordion-button p-2 fs-2 ps-2  text-bg-blue bg-blue" type="button" data-bs-toggle="collapse"
                                data-bs-target="#{{ $formSearchId }}" aria-expanded="true"
                                aria-controls="{{ $formSearchId }}">
                                {{ $page_title }}
                            </button>
                        </h2>
                        <div id="{{ $formSearchId }}" class="accordion-collapse collapse show "
                            aria-labelledby="{{ $formSearchId }}-header" data-bs-parent="#{{ $formSearchId }}-parent">
                            <div class="accordion-body p-2 bg-white" x-data="{ AdvancedSearch: false }">
                                {!! form_render($itemManager->getFormSearch(), $formSearch, null) !!}
                                <div style="display: none" x-show="AdvancedSearch">
                                    Advanced Search
                                </div>
                                <div class="text-end mb-2">
                                    <button class="btn btn-warning" @click="AdvancedSearch=!AdvancedSearch">
                                        Advanced Search </button>
                                    <a wire:click='doSearch()' class="btn btn-primary">Search</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-2 fs-2 ps-2  text-bg-blue bg-blue rounded-2"> {{ $page_title }}</div>
            @endif
        </div>

        <div class="page-body">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body {{ $cardBodyClass }}">
                        {!! table_render($itemManager, $dataItems, $dataFilters, $dataSorts, $formTable, $selectIds) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
