<div class="row row-cards row-deck">
    @if ($datas)
        @foreach ($datas as $item)
        <livewire:sokeio::platform.item wire:key="theme-{{ $item->id }}" itemId="{{ $item->id }}"
            itemType="theme" :routeName="$routeName">
        @endforeach
    @endif
</div>
