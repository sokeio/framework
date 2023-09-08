@php
    $dataOptions = $item->getDataOption();
    $modelField = $item->getModelField();
    $toField = isset($dataOptions['toField']) ? $dataOptions['toField'] : '';
    $fromField = isset($dataOptions['fromField']) ? $dataOptions['fromField'] : '';
@endphp
<div {!! $item->getAttributeContent() !!}>
    @if (!$item->getManager()->IsTable())
        <label class="form-label">{{ $item->getTitle() ?? $item->getField() }}</label>
    @endif
    <input wire:ignore wire:flatpickr wire:flatpickr.options='@json($item->getFieldOption())' {!! $item->getAttribute() ?? '' !!}
        class="form-control" wire:model='{{ $modelField }}' name="{{ $modelField }}"
        placeholder="{{ $item->getPlaceholder() }}" x-init="@if($toField)
        $watch('$wire.{{ $item->getModelField($toField) }}',
            function() {
                $el.livewire____flatpickr.set('maxDate', $wire.{{ $item->getModelField($toField) }});
            });
        @endif @if($fromField)
        $watch('$wire.{{ $item->getModelField($fromField) }};',
            function() {
                $el.livewire____flatpickr.set('minDate', $wire.{{ $item->getModelField($fromField) }});
            });
        @endif">
    @error($modelField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>
