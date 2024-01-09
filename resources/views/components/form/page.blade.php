<div class="page-body mt-2">
    <div class="container-fluid ">
        <div @if ($formUIClass) class="{{ $formUIClass }}" @endif>
            @includeIf('sokeio::components.layout', ['layout' => $layout])
            @includeIf('sokeio::components.layout', ['layout' => $footer])
        </div>
    </div>
</div>
