@php
    $chartData = isset($WidgetData['chartData']) ? $WidgetData['chartData'] : [];
@endphp
<div class="chart-apexcharts" wire:apexcharts='{!! json_encode($chartData) !!}'
    style="min-height: {{ isset($WidgetSetting['minHeight']) && $WidgetSetting['minHeight'] != '' ? $WidgetSetting['minHeight'] : '100' }}px;">
</div>
