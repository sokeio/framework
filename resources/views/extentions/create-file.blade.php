<div>
    <div class="modal-body">

        <div class="mb-3">
            <label class="form-label">Template File In {{ $ExtentionType }}</label>
            <select wire:model='InputTemplate' class="form-select">
                <option selected>Open this select Template</option>
                @foreach ($templates as $temp)
                    <option value="{{ $temp }}">{{ $temp }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Name File</label>
            <input wire:model='InputName' type="text" class="form-control" name=""
                placeholder="Input {{ $ExtentionType }}">
        </div>
        <div class="mb-3">
            <div class="bg-dark text-white text-bold px-3 py-2 border border-1 border-blue  rounded-2">
                php artisan so:make-file <span x-text="$wire.InputName"></span> -b {{ $ExtentionId }} -t
                {{ $ExtentionType }} -te <span x-text="$wire.InputTemplate"></span>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
        <button wire:click='doCreate()' type="button" class="btn btn-primary">Add {{ $ExtentionType }}</button>
    </div>
</div>
