@if ($items->count() == 1)
    @php
        $item = $items->first();
        $name = explode('.', $item->name);
        $name = end($name);
    @endphp
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-azure text-azure-fg  p-2 px-3">
            <h3 class="card-title"> {{ $name }}</h3>
        </div>
    </div>
@else
    @php
        $groups = $items
            ->where(function ($item) use ($level) {
                return count(explode('.', $item->name)) > $level;
            })
            ->groupBy(function ($item) use ($level) {
                return explode('.', $item->name)[$level];
            });
    @endphp

    @foreach ($groups as $key => $group)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-azure text-azure-fg  p-2 px-3">
                <h3 class="card-title"> {{ str($key)->replace('-', ' ')->title() }}</h3>
            </div>
            <div class="card-body p-2">
                @include('sokeio::livewire.permission-list.tree', [
                    'items' => $group,
                    'level' => $level + 1,
                ])
            </div>
        </div>
    @endforeach
@endif
