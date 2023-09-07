<div class="page-body">
    <div class="container-fluid">
        <div class="card">
            <div class="row g-0">
                <div class="col-3 d-none d-md-block border-end">
                    <div class="card-body">
                        <h4 class="subheader">settings</h4>
                        <div class="list-group list-group-transparent">
                            @foreach ($formWithTitle as $item)
                                <button wire:click='ChangeTab("{{ $item['key'] }}")'
                                    class="list-group-item list-group-item-action d-flex align-items-center @if ($tabActive == $item['key']) active @endif">{{ $item['title'] ?? $item['key'] }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col d-flex flex-column">
                    <livewire:theme::pages.setting.form :$tabActive wire:key='setting-{{ $tabActiveIndex }}' />
                </div>
            </div>
        </div>
    </div>
</div>
</div>
