<div class="{{ columnSize($widget->getOptionByKey('column', 'col12')) }} mb-1"
    @if ($poll = $widget->getOptionByKey('poll')) wire:poll.{{ $poll }} @endif>
    <div @if ($ratio = $widget->getOptionByKey('ratio')) class="ratio ratio-{{ $ratio }}" @endif class="">
        {!! $widget->render() !!}
    </div>
</div>
