@php
    $chartData = isset($chartData) ? $chartData : [];
@endphp
<div class="card card-sm">
    <div class="card-body">
        <div class="chart-apexcharts" wire:apexcharts='{!! json_encode($chartData) !!}'>
        </div>
    </div>
</div>
