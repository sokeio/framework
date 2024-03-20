<div class="card card-md card-bo" wire:key='step-1'>
    <div class="card-body text-center py-2 p-sm-2">
        <h1 class="mt-2">@lang('Database Setting')</h1>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">@lang('Connection Type')</label>
            <select wire:model='dbConnection' class="form-select mb-0">
                <option value="mysql">Mysql</option>
                <option value="sqlite">sqlite</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('Host')</label>
            <input wire:model='dbHost' class=" form-control" value="127.0.0.1" />
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('Port')</label>
            <input wire:model='dbPort' class=" form-control" value="3306" />
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('Database')</label>
            <input wire:model='dbName' class=" form-control" value="" />
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('Username')</label>
            <input wire:model='dbUsername' class=" form-control" value="" />
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('Password')</label>
            <input wire:model='dbPass' class=" form-control" value="" />
        </div>
    </div>
</div>
