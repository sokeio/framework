<div {!! $manager->getAttributeContent() !!} wire:key='short-code-{{ time() }}'>
    @includeIf($manager->getView(), [
        'shortcodeData' => $manager->getShortcodeData(),
    ])
    @php
        $manager->Data(null)->ClearCache();
    @endphp
</div>
