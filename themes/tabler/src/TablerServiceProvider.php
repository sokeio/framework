<?php

namespace SokeioTheme\Tabler;

use Illuminate\Support\ServiceProvider;
use Sokeio\Laravel\ServicePackage;
use Sokeio\Concerns\WithServiceProvider;
use Sokeio\Facades\MenuRender;
use Sokeio\Menu\MenuBuilder;
use Sokeio\Menu\MenuItemBuilder;

class TablerServiceProvider extends ServiceProvider
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
    private function bootGate()
    {
        if (!$this->app->runningInConsole()) {
            addFilter(PLATFORM_PERMISSION_CUSTOME, function ($prev) {
                return [
                    ...$prev
                ];
            });
        }
    }
    public function packageBooted()
    {
        $this->bootGate();
        MenuRender::renderCallback(function (MenuBuilder $menu) {
            $classActive = $menu->checkActive() ? ' menu-item-show ' : '';
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
        MenuRender::renderItemCallback(function (MenuItemBuilder $item) {
            if (!$item->checkView()) {
                return;
            }
            $itemActiveClass = ' menu-item-active';
            $classActive = $item->checkActive() ? ' menu-item-show ' : '';
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
                        echo '<i class="ti ti-sticker-2 fs-2"></i>';
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
                        echo '<a class="nav-link   ' . $classActive . '" href="#" sokeio:component="' . $item->getValueLink() . '">';
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
    }
}
