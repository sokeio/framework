<div class="table-page">
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title ps-2">
                        {{ page_title() }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        @if ($buttons = $itemManager->getButtonOnPage())
                            @foreach ($buttons as $item)
                                {!! $item->render() !!}
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
            <div class="card card-borderless">
                <div class="card-body">
                    {!! form_render($itemManager, $form, $dataId) !!}
                </div>
                <div class="card-footer text-center">
                    <button wire:click='doSave()' class="btn btn-primary">{!! $itemManager->getButtonSaveText() !!}</button>
                </div>
            </div>
        </div>
    </div>
</div>
