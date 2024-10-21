<?php

namespace SokeioTheme\Admin\Page\SystemAccess\Role;

use Sokeio\Support\Livewire\PageInfo;
use Sokeio\UI\Common\Div;
use Sokeio\UI\WithUI;

#[PageInfo(admin: true, auth: true,  title: 'Role Form')]
class Edit extends \Sokeio\Page
{
    use WithUI;
    protected function setupUI()
    {
        return [
            Div::init([
                $this->dataId?? 'Nội dung'
            ])
                ->attr('style', 'padding:10px')

        ];
    }
}
