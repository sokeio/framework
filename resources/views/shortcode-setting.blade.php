<div class="p-2"
    x-data='{
        async getShortCodeHtml(){

        let html = await $wire.getShortCodeHtml2()
        return html;
    }
}'>
    <div class="mb-2">
        <label class="form-label">Shortcode:</label>
        <select class=" form-select" wire:model.live='shortcode'>
            <option>None</option>
            @if (isset($shortcodes))
                @foreach ($shortcodes as $key => $item)
                    <option value="{{ $key }}">{{ $key }}</option>
                @endforeach
            @endif
        </select>
    </div>
    @if (isset($shortcodeItem))
        <div class="row" wire:key='{{ $shortcode }}'>
            <div class="col-12">
                <div class=" p-2">
                    <label class="form-label">Attributes:</label>
                    @foreach ($shortcodeItem->getItems() as $item)
                        {!! field_render($item) !!}
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <div class="mb-2">
        <label class="form-label">Children:</label>
        <textarea wire:tinymce='{}' wire:tinymce-model="children" class="form-control" wire:model='children' name="children"
            placeholder="children"></textarea>
    </div>
    <div class=" text-center mb-2"><button class="btn btn-blue"
            @click="{{ $callbackEvent }}(await getShortCodeHtml())">Save
            Shortcode</button></div>

</div>
