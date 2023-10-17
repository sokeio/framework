<?php

namespace ByteTheme\Admin;

use Illuminate\Support\ServiceProvider;
use BytePlatform\Laravel\ServicePackage;
use BytePlatform\Builders\Menu\MenuBuilder;
use BytePlatform\Builders\Menu\MenuItemBuilder;
use BytePlatform\Facades\Menu;
use BytePlatform\Concerns\WithServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    use WithServiceProvider;

    public function configurePackage(ServicePackage $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('theme')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }
    public function configurePackaged()
    {
    }
    public function extending()
    {
    }
    public function packageRegistered()
    {
        $this->extending();
    }
    private function bootGate()
    {
        if (!$this->app->runningInConsole()) {
            add_filter(PLATFORM_PERMISSION_IGNORE, function ($prev) {
                return [
                    'admin.dashboard',
                    'admin.change-password-account',
                    ...$prev
                ];
            });
            add_filter(PLATFORM_PERMISSION_CUSTOME, function ($prev) {
                return [
                    'admin.user-change-status',
                    'admin.role-change-status',
                    'admin.permission-load-data',
                    ...$prev
                ];
            });
        }
    }
    public function packageBooted()
    {
        $this->bootGate();

        Menu::renderCallback(function (MenuBuilder $menu) {
            $classActive = $menu->checkActive() ? 'show' : '';
            if ($menu->checkSub()) {
                echo '<div id="' . $menu->getId() . '" class="dropdown-menu ' . $classActive . '" data-bs-popper="static">';
                foreach ($menu->getItems() as $_item) {
                    echo $_item->toHtml();
                }
                echo '</div>';
            } else {
                echo '<ul id="' . $menu->getId() . '" class="navbar-nav ' . $classActive . '">';
                foreach ($menu->getItems() as $_item) {
                    echo $_item->toHtml();
                }
                echo '</ul>';
            }
        });
        Menu::renderItemCallback(function (MenuItemBuilder $item) {
            $itemActiveClass=' bg-azure text-azure-fg ';
            $classActive = $item->checkActive() ? 'show' : '';
            if (!$item->checkView()) return;
            if ($item->getParent()->checkSub()) {
                if ($item->checkSubMenu()) {
                    echo '<div id="' . $item->getId() . '" class="dropend ' . $classActive . '" data-sort="' . $item->getValueSort() . '">';
                    echo '<a class="dropdown-item dropdown-toggle" href="#' . $item->getSubMenu()->getId() . '" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">';
                    echo $item->getValueText();
                    echo '</a>';
                    echo $item->getSubMenu()->toHtml();
                    echo '</div>';
                } else {
                    if ($classActive != '') {
                        $classActive .= $itemActiveClass;
                    }
                    echo '<a wire:navigate id="' . $item->getId() . '" class="dropdown-item  ' . $classActive . '" href="' . $item->getValueLink() . '" data-sort="' . $item->getValueSort() . '">';
                    echo $item->getValueText();
                    echo '</a>';
                }
            } else {
                if ($item->checkSubMenu()) {
                    echo '<li id="' . $item->getId() . '" class="nav-item dropdown" data-sort="' . $item->getValueSort() . '">';
                    echo '<a class="nav-link dropdown-toggle   ' . $classActive . '" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">';
                    if ($icon = $item->getValueIcon()) {
                        echo '<span class="nav-link-icon d-md-none d-lg-inline-block">';
                        echo $icon;
                        echo '</span>';
                        echo '<span class="nav-link-title">';
                        echo $item->getValueText();
                        echo '</span>';
                    } else {
                        echo '<span class="nav-link-icon d-md-none d-lg-inline-block">';
                        echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-border-all" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                        <path d="M4 12l16 0"></path>
                        <path d="M12 4l0 16"></path>
                     </svg>';
                        echo '</span>';
                        echo '<span class="nav-link-title">';
                        echo $item->getValueText();
                        echo '</span>';
                    }
                    echo '</a>';
                    echo $item->getSubMenu()->toHtml();
                    echo '</li>';
                } else {
                    if ($classActive != '') {
                        $classActive .= $itemActiveClass;
                    }
                    if ($item->getValueType() == MenuItemBuilder::ITEM_LINK || $item->getValueType() == MenuItemBuilder::ITEM_ROUTE) {
                        echo '<li id="' . $item->getId() . '" class="nav-item" data-sort="' . $item->getValueSort() . '">';
                        echo '<a  wire:navigate class="nav-link   ' . $classActive . '" href="' . $item->getValueLink() . '">';
                        if ($icon = $item->getValueIcon()) {
                            echo '<span class="nav-link-icon d-md-none d-lg-inline-block">';
                            echo $icon;
                            echo '</span>';
                            echo '<span class="nav-link-title">';
                            echo $item->getValueText();
                            echo '</span>';
                        } else {
                            echo '<span class="nav-link-icon d-md-none d-lg-inline-block">';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-border-all" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                            <path d="M4 12l16 0"></path>
                            <path d="M12 4l0 16"></path>
                         </svg>';
                            echo '</span>';
                            echo '<span class="nav-link-title">';
                            echo $item->getValueText();
                            echo '</span>';
                        }
                        echo '</a>';
                        echo '</li>';
                    } else if ($item->getValueType() == MenuItemBuilder::ITEM_COMPONENT) {
                        echo '<li id="' . $item->getId() . '" class="nav-item" data-sort="' . $item->getValueSort() . '">';
                        echo '<a class="nav-link   ' . $classActive . '" href="#" byte:component="' . $item->getValueLink() . '">';
                        if ($icon = $item->getValueIcon()) {
                            echo '<span class="nav-link-icon d-md-none d-lg-inline-block">';
                            echo $icon;
                            echo '</span>';
                            echo '<span class="nav-link-title">';
                            echo $item->getValueText();
                            echo '</span>';
                        } else {
                            echo '<span class="nav-link-icon d-md-none d-lg-inline-block">';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-border-all" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                            <path d="M4 12l16 0"></path>
                            <path d="M12 4l0 16"></path>
                         </svg>';
                            echo '</span>';
                            echo '<span class="nav-link-title">';
                            echo $item->getValueText();
                            echo '</span>';
                        }
                        echo '</a>';
                        echo '</li>';
                    }
                }
            }
        });
        Menu::Register(function () {
            if (byte_is_admin()) {
                Menu::route('admin.dashboard', __('byte::sidebars.dashboard'), '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dashboard" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                <path d="M13.45 11.55l2.05 -2.05"></path>
                <path d="M6.4 20a9 9 0 1 1 11.2 0z"></path>
             </svg>', [], '', 1);
                menu::subMenu(__('byte::sidebars.user'), '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-shield" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M6 21v-2a4 4 0 0 1 4 -4h2"></path>
                <path d="M22 16c0 4 -2.5 6 -3.5 6s-3.5 -2 -3.5 -6c1 0 2.5 -.5 3.5 -1.5c1 1 2.5 1.5 3.5 1.5z"></path>
                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
             </svg>', function (MenuBuilder $menu) {
                    $menu->setTargetId('user_menu');
                    $menu->route(['name' => 'admin.user-list', 'params' => []], __('byte::sidebars.user'), '', [], 'admin.user-list');
                    $menu->route(['name' => 'admin.role-list', 'params' => []], __('byte::sidebars.role'), '', [], 'admin.role-list');
                    $menu->route(['name' => 'admin.permission-list', 'params' => []], __('byte::sidebars.permission'), '', [], 'admin.permission-list');
                }, 9999999999999);
                menu::subMenu(__('byte::sidebars.appearance'), '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brush" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M3 21v-4a4 4 0 1 1 4 4h-4"></path>
                <path d="M21 3a16 16 0 0 0 -12.8 10.2"></path>
                <path d="M21 3a16 16 0 0 1 -10.2 12.8"></path>
                <path d="M10.6 9a9 9 0 0 1 4.4 4.4"></path>
             </svg>', function (MenuBuilder $menu) {
                    $menu->setTargetId('system_appearance_menu');
                    $menu->route(['name' => 'admin.theme', 'params' => []], 'Themes', '', [], 'admin.theme');
                    $menu->route(['name' => 'admin.theme-options', 'params' => []], 'Theme Options', '', [], 'admin.theme-options');
                }, 9999999999999);
                menu::subMenu('Extension', '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-puzzle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M4 7h3a1 1 0 0 0 1 -1v-1a2 2 0 0 1 4 0v1a1 1 0 0 0 1 1h3a1 1 0 0 1 1 1v3a1 1 0 0 0 1 1h1a2 2 0 0 1 0 4h-1a1 1 0 0 0 -1 1v3a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1v-1a2 2 0 0 0 -4 0v1a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1v-3a1 1 0 0 1 1 -1h1a2 2 0 0 0 0 -4h-1a1 1 0 0 1 -1 -1v-3a1 1 0 0 1 1 -1"></path>
             </svg>', function (MenuBuilder $menu) {
                    $menu->setTargetId('system_extension_menu');
                    $menu->route(['name' => 'admin.module', 'params' => []], 'Modules', '', [], 'admin.module');
                    $menu->route(['name' => 'admin.plugin', 'params' => []], 'Plugins', '', [], 'admin.plugin');
                }, 9999999999999);
                menu::subMenu('Settings', '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
   <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
   <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
</svg>', function (MenuBuilder $menu) {
                    $menu->setTargetId('system_setting_menu');
                    $menu->route('admin.setting', 'System Setting', '', [], 'admin.setting');
                }, 99999999999999);
            }
        });
    }
}
