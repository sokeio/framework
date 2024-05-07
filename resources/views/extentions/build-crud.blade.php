<div class="container">
    <div class="row">
        <div class="col-auto">
            Model:
        </div>
        <div class="col-auto">
            <select wire:model='modelCurent' class="form-select">
                <option selected>Open this select Model</option>
                @foreach ($models as $temp)
                    <option value="{{ $temp }}">{{ $temp }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
