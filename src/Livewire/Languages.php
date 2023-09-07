<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Facades\Locale;

class Languages extends Component
{
    public function DoSwtich($locale)
    {
        Locale::SwitchLocale($locale);
        return $this->redirectCurrent();
    }
    public function render()
    {
        return view_scope('byte::languages', [
            'locales' => Locale::SupportedLocales(),
            'currentLocale' => Locale::CurrentLocale()
        ]);
    }
}
