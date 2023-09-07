<div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Name {{ $ExtentionType }}</label>
            <input wire:model='InputName' type="text" class="form-control" name=""
                placeholder="Input {{ $ExtentionType }}">
        </div>
        <div class="mb-3">
            <div class="bg-dark text-white text-bold px-3 py-2 border border-1 border-blue  rounded-2">
                php artisan mb:{{ $ExtentionType }} <span x-text="$wire.InputName"></span> -a true -f true
            </div>
            <span> -a : active </span>
            <span> -f : force</span>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
        <button wire:click='doCreate()' type="button" class="btn btn-primary">Add {{ $ExtentionType }}</button>
    </div>
</div>
