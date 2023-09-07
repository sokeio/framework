<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Widget Type</label>
            <select class="form-select" wire:model.live='form.widgetType'
                @if ($form->widgetId) disabled @endif>
                <option value="">No Widget</option>
                @foreach ($widgets as $key => $title)
                    <option value="{{ $key }}">{{ $title }}</option>
                @endforeach
            </select>
        </div>
        @if ($WidgetItem)
            {{-- <div class="mb-3 p-2 text-bg-bitbucket">
                {{ $WidgetItem->getName() }}
            </div> --}}
            <div class="row mb-3">
                @foreach ($WidgetItem->getItems() as $item)
                    {!! field_render($item) !!}
                @endforeach
            </div>
            <div class="row">
                @if ($form->widgetId)
                    <div class="col text-center">
                        <a wire:click='RemoveWidget()' class="btn btn-danger">
                            Remove Widget
                        </a>
                    </div>
                @endif
                <div class="col text-center">
                    <a wire:click='UpdateWidget()' class="btn btn-primary">
                        Update Widget
                    </a>
                </div>

            </div>
        @else
            @if ($form->widgetId)
                <a wire:click='RemoveWidget()' class="btn btn-danger">
                    Remove Widget
                </a>
            @endif
        @endif

    </div>
</div>
