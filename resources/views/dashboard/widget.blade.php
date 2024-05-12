<div class="{{ columnSize($widget->getOptionByKey('column', 'col12')) }} mb-1"
    @if ($poll = $widget->getOptionByKey('poll')) wire:poll.{{ $poll }} @endif>
    {!! $widget->render() !!}
</div>
