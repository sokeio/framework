<a @if ($link = $column->getLink()) href="{{ $link }}" @endif
    {!! $column->getAttribute() ?? '' !!} {!! $column->getWireAttribute() ?? '' !!}
    {!! $column->getSokeAttribute() ?? '' !!} class=" {{ $column->getClassName() ?? '' }}
    {{ $column->getClassButton() ?? '' }}">
    {!! $column->getIcon() !!}
    {!! $column->getTitle() ?? $column->getName() !!}
</a>
