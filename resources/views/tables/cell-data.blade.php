<div>
    @if ($manager->getEditInTable() && $column->getWhen())
        @php
            $formTable->{$item->id}->setDataModel($item);
        @endphp
        {!! field_render($column, $formTable->{$item->id}, $item->id) !!}
    @else
        {!! $column->getDataText() !!}
    @endif
</div>
