@php
    $page_title = $itemManager->getTitle();
    $formSearch = 'formSearch-' . time();
@endphp
<div class="table-page">
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="accordion" wire:ignore id="{{ $formSearch }}-parent">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="{{ $formSearch }}-header">
                        <button class="accordion-button p-2 fs-2 ps-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#{{ $formSearch }}" aria-expanded="true"
                            aria-controls="{{ $formSearch }}">
                            {{ $page_title }}
                        </button>
                    </h2>
                    <div id="{{ $formSearch }}" class="accordion-collapse collapse show"
                        aria-labelledby="{{ $formSearch }}-header" data-bs-parent="#{{ $formSearch }}-parent">
                        <div class="accordion-body p-0">
                            <div class="card table-box-search rounded-0 ">
                                <div class="card-body">
                                    Advanced Search

                                </div>
                                <div class="card-footer p-2">
                                    <div class="row">
                                        <div class="col fw-bold">
                                            <button class="btn btn-warning"> Advanced Search </button>
                                        </div>
                                        <div class="col-auto">
                                            <div class="text-end ">
                                                <a href="#" class="btn btn-secondary">Reset</a>
                                                <a href="#" class="btn btn-primary">Search</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
