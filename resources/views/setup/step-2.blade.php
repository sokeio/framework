<div class="card card-md card-bo" wire:key='step-2'>
    <div class="card-body text-center py-2 p-sm-2">
        <h1 class="mt-2">@lang('Account infomation')</h1>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">@lang('Name')</label>
            <input wire:model='accName' class="form-control" />
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('Email')</label>
            <input wire:model='accEmail' class="form-control" />
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('Password')</label>
            <input type="password" wire:model='accPass' class="form-control" />
        </div>
    </div>
</div>

