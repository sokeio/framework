<div>
    <label class="form-check">
        <input wire:model='{{ $formField }}' name="{{ $formField }}" class="form-check-input"
            type="checkbox" value="{{ data_get($item, $FieldKey) }}">
        <span class="form-check-label">{{ data_get($item, $FieldText) }}</span>
    </label>
</div>