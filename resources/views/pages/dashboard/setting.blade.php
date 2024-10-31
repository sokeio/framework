    <div class="col-lg-2 col-md-3 col-sm-12 sokeio-modal-body-max-height overflow-auto p-0 m-0">
        <div class="text-center" x-data="{
            open: false,
            name: '',
            toggle() {
                this.open = !this.open
            },
            close() {
                this.name = '';
                this.open = false;
            },
            async save() {
                if (this.name) {
                    await $wire.addDashboard(this.name)
                }
                this.close()
            },
            onKeydownEscape() {
                this.close()
            },
            onKeydownEnter() {
                this.save()
            }
        }" @keydown.escape="onKeydownEscape" @keydown.enter="onKeydownEnter">
            <button x-show="!open" class="btn btn-bitbucket" type="button" @click="toggle">
                Add Dashboard
            </button>
            <div x-show="open" style="display: none" class="input-group mb-2">
                <input type="text" x-model="name" class="form-control border-light" aria-label="Dashboard"
                    placeholder="Dashboard">
                <button class="btn btn-primary" type="button" @click="save">Save</button>
            </div>
        </div>
        <div class="pt-2 " x-data="{
            selectDashboard(id) {
                $wire.dashboardId = id;
                $wire.selectDashboard(id);
            },
        }" wire:ignore.self x-init="dashboard = {{ $dashboardId }}">
            <input type="text" class="form-control" placeholder="Search…" aria-label="Search">
            <div class="nav flex-column nav-pills scrollable border mt-2" role="tablist"
                wire:key='dashboard-count-{{ $dashboards->count() }}'>
                @foreach ($dashboards as $item)
                    <a wire:key="dashboard-{{ $item->id }}" @click="selectDashboard({{ $item->id }})"
                        class="nav-link text-start mw-100 rounded-0 p-1 cursor-pointer "
                        :class="$wire.dashboardId == {{ $item->id }} ? 'bg-blue text-bg-blue' : ''">
                        {{ $item->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-12 sokeio-modal-body-max-height overflow-auto  p-0 m-0">
        <livewire:sokeio::dashboard-setting wire:key="dashboard-{{ $dashboardId }}"
            dashboardId="{{ $dashboardId }}" />
    </div>
