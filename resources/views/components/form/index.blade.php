<div @if ($formUIClass) class="{{ $formUIClass }}" @endif {!! $formUIAttribute ?? '' !!}>
    @includeIf('sokeio::components.layout', ['layout' => $layout])
    @includeIf('sokeio::components.layout', ['layout' => $footer])
</div>
