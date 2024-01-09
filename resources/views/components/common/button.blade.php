<a @if ($link = $column->getLink()) href="{{ $link }}" @endif {!! $column->getAttribute() ?? '' !!} {!! $column->getWireAttribute() ?? '' !!}
    {!! $column->getSokeAttribute() ?? '' !!}
    class="btn {{ $column->getClassName() ?? '' }} @if ($buttonColor = $column->getButtonColor()) btn{{ $buttonColor }} @else  btn-primary @endif
    @if ($buttonSize = $column->getButtonSize()) btn-{{ $buttonSize }} @endif
    ">
    {!! $column->getTitle() ?? $column->getName() !!}
</a>
