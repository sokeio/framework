<div class="card card-borderless">
    <div class="card-body">
        {!! form_render($itemManager, $form, $dataId) !!}
    </div>
    <div class="card-footer text-center">
        <button wire:click='doSave()' class="btn btn-primary">{!! $itemManager->getButtonSaveText() !!}</button>
    </div>
</div>
