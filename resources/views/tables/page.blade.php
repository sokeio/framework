@php
    $page_title = page_title();
@endphp
<div class="table-page">
    @if ($page_title || $buttonOnPage)
        <div class="page-header d-print-none">
            <div class="container-fluid">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title ps-2">
                            {{ $page_title }}
                        </h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="page-body">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body {{ $cardBodyClass }}">
                    {!! table_render($itemManager, $dataItems, $dataFilters, $dataSorts, $formTable, $selectIds) !!}
                    <div class="row  align-items-center">
                        <div class="col-auto px-3"></div>
                        <div class="col-auto py-3">
                            <select wire:model.live='pageSize' class="form-select form-select-sm">
                                @foreach ($pageSizeList as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            {{ $dataItems->links('byte::pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
