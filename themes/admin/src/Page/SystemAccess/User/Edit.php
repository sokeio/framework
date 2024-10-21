<?php

namespace SokeioTheme\Admin\Page\SystemAccess\User;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Div;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'User Form')]
class Edit extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            Div::init([
               "ac"

            ])
                ->attr('style', 'padding:10px')

        ];
    }
}
