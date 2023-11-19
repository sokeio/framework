<div class="card card-md card-bo" wire:key='step-3'>
    <div class="card-body text-center py-2 p-sm-2">
        <h1 class="mt-2">@lang('System infomation')</h1>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">@lang('Site name')</label>
            <input wire:model='site_name' class="form-control" />
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('Site Description')</label>
            <textarea wire:model='site_description' class=" form-control" rows="3"></textarea>
        </div>
    </div>
</div>