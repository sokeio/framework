<div class="bg-white p-3">
    @foreach ($positions as $item)
        <h3 class="text-uppercase">{{ $item['title'] }}
            <a sokeio:modal="{{ route('admin.form-widget-setting', [
                'position' => $item['id'],
                'dashboardId' => $dashboardId,
            ]) }}"
                sokeio:modal-size="modal-md" sokeio:modal-title="@lang('Add Widget')"
                class="btn btn-primary btn-sm float-end">@lang('Add')</a>
        </h3>
        <div class="border p-1 mb-3 rounded " style="min-height: 50px">
            <div class="row {{ $item['class'] ?? '' }}" wire:sortable x-data="{
                onSortable(items) {
                    $wire.updatePosition('{{ $item['id'] }}', items);
                }
            }">
                @foreach ($widgets as $widget)
                    @if ($widget['position'] == $item['id'])
                        <div class="{{ columnSize($widget['options']['column']) }} mb-1" wire:sortable.item
                            data-sortable-id="{{ $widget['id'] }}">
                            <div class="card bg-azure text-azure-fg">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-auto">
                                            <span class="bg-white text-white-fg avatar">
                                                <i
                                                    class="text-white-fg {{ $widget['options']['icon'] ?? '' }} fs-2"></i>
                                            </span>
                                        </div>
                                        <div class="col">{{ $widget['options']['title'] ?? '' }}</div>
                                    </div>
                                    <div> {{ $widget['type'] ?? '' }}</div>
                                    <div class="position-absolute top-0 pt-1 pe-1 end-0">
                                        <a sokeio:modal="{{ route('admin.form-widget-setting', [
                                            'dataId' => $widget['id'],
                                            'dashboardId' => $dashboardId,
                                        ]) }}"
                                            sokeio:modal-size="modal-md" sokeio:modal-title="@lang('Edit Widget')"
                                            class="btn btn-success btn-sm">@lang('Edit')</a>
                                        <a wire:click="deleteWidget('{{ $widget['id'] }}', '{{ $item['id'] }}')"
                                            class="btn btn-danger btn-sm">@lang('Delete')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
    <div class="text-center">
        <button wire:click="saveSettings" class="btn btn-primary">@lang('Save Settings')</button>
    </div>
</div>
