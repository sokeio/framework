<div x-data="{}">
    @foreach ($allPermissions as $key => $item)
        <div class="card mb-4">
            <div class="card-header bg-azure text-azure-fg  p-2 px-3">
                <h3 class="card-title"> {{ str($key)->replace('-', ' ')->title() }}</h3>
            </div>
            <div class="card-body p-2">
                @php
                    $groups = $item->groupBy(function ($item) {
                        return explode('.', $item->name)[2];
                    });
                @endphp
                <div class="row">
                    @foreach ($groups as $keyBox => $box)
                        <div class="col-6 col-md-4 col-lg-3">
                            @include('sokeio::livewire.permission-list.tree', [
                                'items' => $box,
                                'level' => 3,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
