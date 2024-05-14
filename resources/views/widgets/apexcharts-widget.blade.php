@php
    $chartData = isset($WidgetData['chartData']) ? $WidgetData['chartData'] : [];
@endphp
<div class="card card-sm">
    <div class="card-body">
        <div class="chart-apexcharts" wire:apexcharts='{!! json_encode($chartData) !!}'>
        </div>
    </div>
</div>
