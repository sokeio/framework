<div class="p-2">
    <div class="row align-items-center">
        <div class="col-auto">
            <span class="bg-primary text-white avatar">
                @if (isset($WidgetIcon))
                    {!! $WidgetIcon !!}
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2">
                        </path>
                        <path d="M12 3v3m0 12v3"></path>
                    </svg>
                @endif
            </span>
        </div>
        <div class="col">
            <div class="font-weight-medium">
                {{ isset($widgetTitle) ? $widgetTitle : '' }}
            </div>
            <div class="text-secondary">
                {{ isset($widgetData) ? $widgetData : 0 }}
            </div>
        </div>
    </div>

</div>
