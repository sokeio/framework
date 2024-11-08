@if ($items->count() <= 1)
    @php
        $item = $items->first();
        $name = explode('.', $item->name);
        $name = end($name);
    @endphp
    <div class="permission-item">
        <input class="form-check-input m-0" type="checkbox" value="{{ $item->slug }}">
        <span class="form-check-label">{{ $name }}</span>
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
        <div class="permission-box">
            <div class="permission-box-header">
                <input class="form-check-input m-0" type="checkbox"
                    id="permission-box-{{ $key }}-{{ $level }}">
                <span class="form-check-label" for="permission-box-{{ $key }}-{{ $level }}">
                    {{ str($key)->replace('-', ' ')->title() }}</span>
            </div>
            <div class="permission-box-body">
                @include('sokeio::livewire.permission-list.tree', [
                    'items' => $group,
                    'level' => $level + 1,
                ])
            </div>
        </div>
    @endforeach
@endif
