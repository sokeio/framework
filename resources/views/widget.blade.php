<div class="{{ column_size(isset($WidgetSetting['column']) ? $WidgetSetting['column'] : 'col') }} widget-view"
    @if (isset($WidgetSetting['poll']) && $WidgetSetting['poll'] != '') wire:{!! $WidgetSetting['poll'] !!} @endif
    wire:sortable.item="{{ $WidgetSetting['widgetId'] }}" wire:key="widget-{{ $WidgetSetting['widgetId'] }}">
    <div class="card">
        <div class="card-body p-1">
            @if (!$locked)
                <span wire:sortable.handle class=" position-absolute" style="z-index: 10;top:2px;right:2px">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-move" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 9l3 3l-3 3"></path>
                        <path d="M15 12h6"></path>
                        <path d="M6 9l-3 3l3 3"></path>
                        <path d="M3 12h6"></path>
                        <path d="M9 18l3 3l3 -3"></path>
                        <path d="M12 15v6"></path>
                        <path d="M15 6l-3 -3l-3 3"></path>
                        <path d="M12 3v6"></path>
                    </svg>
                </span>
                <span byte:modal="{{ route('admin.widget-setting', ['widgetId' => $WidgetSetting['widgetId']]) }}"
                    byte:modal-size="modal-lg" byte:modal-title="Setting Widget" class="position-absolute"
                    style="z-index:100;top:5px;right:25px">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tool" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5">
                        </path>
                    </svg>
                </span>
            @endif
            <div>
                @if ($WidgetItem)
                    @includeIf($WidgetItem->getView(), [
                        'WidgetData' => $WidgetItem->getWidgetData(),
                        'WidgetSetting' => $WidgetSetting,
                        'widgetId' => $widgetId,
                    ])
                    @php
                        $WidgetItem->Data(null)->ClearCache();
                    @endphp
                @endif
            </div>
        </div>
    </div>
</div>
