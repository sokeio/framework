<div x-data="{}" class="permission-container">
    <h2>(Not Implemented)</h2>
    @foreach ($allPermissions as $key => $item)
        <div class="permission-card">
            <div class="permission-card-header">
                <input class="form-check-input m-0" type="checkbox" id="permission-card-{{ $key }}">
                <span class="form-check-label" for="permission-card-{{ $key }}">
                    {{ str($key)->replace('-', ' ')->title() }}
                </span>
            </div>
            <div class="permission-card-body">
                @include('sokeio::livewire.permission-list.tree', [
                    'items' => $item,
                    'level' => 3,
                ])
            </div>
        </div>
    @endforeach
</div>
