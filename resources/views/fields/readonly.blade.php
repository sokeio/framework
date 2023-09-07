<div {!! $item->getAttributeContent() !!}>
    @if ($item->getManager()->IsTable())
        {!! $item->getDataText() !!}
    @else
        <label class="form-label">{{ $item->getTitle() ?? $item->getField() }}</label>
        <div class="form-control" class="form-control" name="{{ $item->getModelField() }}"
            placeholder="{{ $item->getPlaceholder() }}">
            {!! $item->getDataText() !!}
        </div>
    @endif
</div>
