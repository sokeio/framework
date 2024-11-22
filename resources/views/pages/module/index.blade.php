<div class="row row-cards row-deck">
    @if ($datas)
        @foreach ($datas as $item)
            <livewire:sokeio::platform.item wire:key="module-{{ $item->id }}" itemId="{{ $item->id }}"
                itemType="module" :routeName="$routeName">
        @endforeach
    @endif
</div>
