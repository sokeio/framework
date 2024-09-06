<?php

namespace Sokeio;

use Sokeio\Theme;

class BadgeItem
{
    public function getBadge()
    {
        // return $this->badge;
    }
    public function render()
    {
        return Theme::view('sokeio::partials.badge', ['badge' => $this])->render();
    }
}
