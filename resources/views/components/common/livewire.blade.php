@php
    $title = $column->getTitle();
    $collapse = $column->getCollapse();
@endphp
<div x-data="{ showCollapse: {{ $collapse === true && $title ? 'true' : 'false' }} }">
    @if ($title)
        <label class="form-label position-relative mb-3">
            {!! $title !!}
            @if ($collapse !== null)
                <span title="Toggle" class="position-absolute top-0 end-0 badge bg-primary"
                    x-on:click="showCollapse = !showCollapse">
                    <i class="ti ti-chevrons-down fs-2 text-bg-primary" x-show="showCollapse"></i>
                    <i class="ti ti-chevrons-up fs-2 text-bg-primary" x-show="!showCollapse"></i>
                </span>
            @endif
        </label>
    @endif
    <div x-show="!showCollapse">
        @livewire($column->getContent(), $column->getParams() ?? [])
    </div>
</div>
