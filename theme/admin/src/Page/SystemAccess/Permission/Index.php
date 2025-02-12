<?php

namespace SokeioTheme\Admin\Page\SystemAccess\Permission;

use Sokeio\Platform;
use Sokeio\Core\Attribute\AdminPageInfo;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\LivewireUI;
use Sokeio\UI\PageUI;
use Sokeio\UI\WithUI;

#[AdminPageInfo(
    title: 'Permission',
    menu: true,
    menuTitle: 'Permissions',
    icon: 'ti ti-shield-lock',
)]
class Index extends \Sokeio\Page
{
    use WithUI;
    public function updatePermissions()
    {
        Platform::gate()->updatePermission(true);
    }
    protected function setupUI()
    {
        return [
            PageUI::make(
                LivewireUI::make("sokeio::permission-list")
            )
            ->enableLoading()
            ->rightUI([
                Button::make()
                    ->label(__('Upadate Permissions'))
                    ->wireClick('updatePermissions')
                    ->className('btn btn-success')
                    ->icon('ti ti-refresh'),

            ])
        ];
    }
}
