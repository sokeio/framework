<div class="bg-white p-3">
    @foreach ($positions as $item)
        <h3 class="text-uppercase">{{ $item['title'] }}
            <a sokeio:modal="{{ route('admin.form-widget-setting') }}" sokeio:modal-size="modal-fullscreen"
                sokeio:modal-title="@lang('Add Widget')" class="btn btn-primary btn-sm float-end">@lang('Add')</a>
        </h3>
        <div class="border p-1 mb-3 rounded ">
            <div class="row {{ $item['class'] ?? '' }}" wire:sortable>
                @foreach ($widgets as $widget)
                    @if ($widget['position'] == $item['id'])
                        <div class="{{ columnSize($widget['options']['column']) }} mb-1"
                            wire:sortable.item="{{ $widget['id'] }}">
                            <div class="card bg-secondary text-bg-secondary">
                                <div class="card-body">
                                    {{ $widget['options']['title'] ?? '' }}
                                    {{ $widget['id'] ?? '' }}
                                    <div class="position-absolute top-0 pt-1 pe-1 end-0">
                                        <a href="#" class="btn btn-success btn-sm">@lang('Edit')</a>
                                        <a href="#" class="btn btn-danger btn-sm">@lang('Delete')</a>
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
        <button class="btn btn-primary">@lang('Save Settings')</button>
    </div>
</div>
