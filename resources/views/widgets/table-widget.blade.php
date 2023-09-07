<div
    style="min-height: {{ isset($WidgetSetting['minHeight']) && $WidgetSetting['minHeight'] != '' ? $WidgetSetting['minHeight'] : '100' }}px;">
    <livewire:byte::widget-table :widgetId="$widgetId" wire:key='form-table-{{ time() }}' />
</div>
