<div>
    <div class="row row-deck row-cards g-2">
        @foreach ($dataItems as $item)
            @includeif('byte::extentions.item', ['item' => $item,'ExtentionType'=>$ExtentionType])
        @endforeach
    </div>

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
