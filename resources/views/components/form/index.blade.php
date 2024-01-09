<div @if($formUIClass) class="{{$formUIClass}}" @endif>
    @includeIf('sokeio::components.layout', ['layout' => $layout])
    @includeIf('sokeio::components.layout', ['layout' => $footer])
</div>
