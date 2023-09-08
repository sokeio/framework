@php
    $dataOptions = $item->getDataOption();
    $modelField = $item->getModelField();
    $toField = isset($dataOptions['toField']) ? $dataOptions['toField'] : '';
    $fromField = isset($dataOptions['fromField']) ? $dataOptions['fromField'] : '';
    $digits = isset($dataOptions['digits']) ? $dataOptions['digits'] : 0;
@endphp
<div {!! $item->getAttributeContent() !!}>
    @if (!$item->getManager()->IsTable())
        <label class="form-label">{{ $item->getTitle() ?? $item->getField() }}</label>
    @endif
    <input x-mask:dynamic="$money($input, '.', ',', {{ $digits }})" {!! $item->getAttribute() ?? '' !!} class="form-control"
        wire:model='{{ $modelField }}' name="{{ $modelField }}" placeholder="{{ $item->getPlaceholder() }}"
        x-data="{
            flg:false
        }"
        x-init="@if ($toField) $watch('$wire.{{ $item->getModelField($toField) }}',
            function() {
                if(flg) return;
                flg=true;
                if( $wire.{{ $modelField }} > $wire.{{ $item->getModelField($toField) }} )
                {
                    $wire.{{ $modelField }} = $wire.{{ $item->getModelField($toField) }};
                }
                flg=false;
            }); @endif @if ($fromField) $watch('$wire.{{ $item->getModelField($fromField) }}',
            function() {
                if(flg) return;
                flg=true;
                if( $wire.{{ $item->getModelField($fromField) }} > $wire.{{ $modelField }}  )
                {
                    $wire.{{ $modelField }} = $wire.{{ $item->getModelField($fromField) }};
                }
                flg=false;
            }); @endif">
    @error($modelField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>
