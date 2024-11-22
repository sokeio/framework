<?php

namespace SokeioTheme\Admin\Page\Account;

use Sokeio\Attribute\PageInfo;
use Sokeio\Theme;

#[PageInfo(admin: true, auth: true, title: 'Account')]
class Index extends \Sokeio\Page
{
       public function render()
    {
        return Theme::view('pages.account.index');
    }
}
