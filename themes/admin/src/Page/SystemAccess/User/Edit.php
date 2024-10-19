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
    public function testRefId()
    {
        $this->alert($this->getRefId());
    }
    protected function setupUI()
    {
        return [
            Div::init([
                Div::init()->text('Edit User'),
                Button::init()
                    ->text(__('demo'))
                    ->wireClick('testRefId()')

            ])
                ->attr('style', 'padding:10px')

                ->className('container')

        ];
    }
}
