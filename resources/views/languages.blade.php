<div class="dropdown mx-2 border rounded ">
    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('sokeio::locales.' . $currentLocale) }} </button>
    <ul class="dropdown-menu">
        @foreach ($locales as $locale)
            <li><button wire:click='doSwtich("{{ $locale }}")' class="dropdown-item"
                    type="button">{{ __('sokeio::locales.' . $locale) }}</button></li>
        @endforeach
    </ul>
</div>
