@php
    $chartData = isset($chartData) ? $chartData : [];
@endphp
<div class="card card-sm">
    @if ($title)
        <div class="card-header py-2">
            <h3 class="card-title align-items-center d-flex">
                <i class="{{ $icon }} fs-1 me-2"></i>
                <span>{{ $title ?? '' }}</span>
            </h3>
        </div>
    @endif
    <div class="card-body">
        <div class="chart-apexcharts" wire:apexcharts='{!! json_encode($chartData) !!}'>
        </div>
    </div>
</div>
