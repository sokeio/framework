@php
    $dataOptions = $item->getDataOption();
    $modelField = $item->getModelField();
    $fieldOption = $item->getFieldOption();
    $domain_name = isset($fieldOption['domain']) ? $fieldOption['domain'] : env('BYTE_SUB_DOMAIN', '');
@endphp
<div {!! $item->getAttributeContent() !!}>
    @if (!$item->getManager()->IsTable())
        <label class="form-label">{{ $item->getTitle() ?? $item->getField() }}</label>
    @endif
    @if ($domain_name)
        <div class="input-group">
            <span class="input-group-text">
                https://
            </span>
            <input {!! $item->getAttribute() ?? '' !!} type="text" class="form-control" wire:model='{{ $modelField }}'
                name="{{ $modelField }}" placeholder="{{ $item->getPlaceholder() }}" autocomplete="off">
            <span class="input-group-text">
                .{{ $domain_name }}
            </span>
        </div>
    @else
        <input {!! $item->getAttribute() ?? '' !!} type="text" class="form-control" wire:model='{{ $modelField }}'
            name="{{ $modelField }}" placeholder="{{ $item->getPlaceholder() }}">
    @endif
    @error($modelField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>
