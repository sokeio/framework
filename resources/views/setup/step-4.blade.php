<div class="card card-md card-bo" wire:key='step-4'>
    <div class="card-body text-center py-2 p-sm-2">
        <h1 class="mt-2">@lang('Extentions')</h1>
    </div>

    <div class="card border-0">
        <div class="card-header rounded-0 text-bg-indigo bg-indigo p-2">
            <h3 class="card-title ">Module</h3>
        </div>
        <div class="list-group list-group-flush p-1">
            @foreach ($modules as $item)
                <div class="list-group-item list-group-item-action p-2">
                    <label class="form-check">
                        <input class="form-check-input" type="checkbox"
                            @if ($item->isVendor()) checked="" disabled @else 
                             wire:model='activeModules.{{ $item->getName() }}' value="1" @endif>
                        <span class="form-check-label">
                            {{ $item->getTitle() }} {{ $item->getVersion() }}
                            @if ($item->isVendor())
                                (Vendor)
                            @endif
                        </span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="card border-0">
        <div class="card-header rounded-0 text-bg-indigo bg-indigo p-2">
            <h3 class="card-title ">Plugin</h3>
        </div>
        <div class="list-group list-group-flush p-1">
            @foreach ($plugins as $item)
                <div class="list-group-item list-group-item-action p-2">
                    <label class="form-check">
                        <input class="form-check-input" type="checkbox"
                            @if ($item->isVendor()) checked="" disabled @else
                            wire:model='activePlugins.{{ $item->getName() }}' value="1" @endif>
                        <span class="form-check-label"> {{ $item->getTitle() }}
                            {{ $item->getVersion() }} @if ($item->isVendor())
                                (Vendor)
                            @endif
                        </span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="card border-0">
        <div class="card-header rounded-0 text-bg-indigo bg-indigo p-2">
            <h3 class="card-title ">Theme(Site)</h3>
        </div>
        <div class="list-group list-group-flush p-3">

            <select wire:model='activeTheme' class="form-select mb-0">
                @foreach ($themes as $item)
                    @if ($item->getName() == 'none' || !($item->hide == true))
                        <option value="{{ $item->getName() }}">{{ $item->getTitle() }} {{ $item->getVersion() }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
</div>
