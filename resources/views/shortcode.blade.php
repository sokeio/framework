<div {!! $manager->getAttributeContent() !!} @if (isset($attrs['poll'])) wire:{{ $attrs['poll'] }} @endif
    wire:key='short-code-{{ time() }}'>
    @includeIf($manager->getView(), [
        'shortcodeData' => $manager->getShortcodeData(),
    ])
    @php
        $manager->Data(null)->ClearCache();
    @endphp
</div>
