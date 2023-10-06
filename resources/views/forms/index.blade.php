<div class="card card-borderless">
    <div class="card-body">
        @if ($itemManager)
            {!! form_render($itemManager, $form, $dataId) !!}
        @endif
    </div>
    <div class="card-footer text-center">
        @if ($itemManager)
            <button wire:click='doSave()' class="btn btn-primary">{!! $itemManager->getButtonSaveText() !!}</button>
        @endif
    </div>
</div>
