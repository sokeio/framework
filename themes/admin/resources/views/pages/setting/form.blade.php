<div>
    <div class="card-body">
        <h2 class="mb-4">{{ $itemManager->getTitle() }}</h2>
        <div class="row">
            {!! form_render($itemManager, $form) !!}
        </div>
    </div>
    <div class="card-footer bg-transparent mt-auto">
        <div class="btn-list justify-content-end">
            <a wire:click='saveSetting()' class="btn btn-primary">
                Save
            </a>
        </div>
    </div>
</div>
