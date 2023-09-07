<div class="dropdown mx-2">
    <button class="btn btn-danger  dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('byte::locales.' . $currentLocale) }} </button>
    <ul class="dropdown-menu">
        @foreach ($locales as $locale)
            <li><button wire:click='DoSwtich("{{ $locale }}")' class="dropdown-item"
                    type="button">{{ __('byte::locales.' . $locale) }}</button></li>
        @endforeach
    </ul>
</div>
