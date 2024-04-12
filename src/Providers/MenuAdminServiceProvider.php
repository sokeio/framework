<?php

namespace Sokeio\Providers;

use Illuminate\Support\ServiceProvider;
use Sokeio\Facades\Menu;
use Sokeio\Facades\Platform;
use Sokeio\Facades\Theme;
use Sokeio\Menu\MenuBuilder;

class MenuAdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        Platform::ready(function () {
            if (sokeioIsAdmin()) {
                Platform::readyAfter(function () {
                    Menu::doRegister();
                });
                Menu::Register(function () {
                    menuAdmin()
                        ->route('admin.dashboard', __('Dashboard'), '<i class="ti ti-dashboard fs-2"></i>', [], '', 1)

                        ->subMenu(__('User'), '<i class="ti ti-user-shield fs-2"></i>', function (MenuBuilder $menu) {
                            $menu->setTargetId('user_menu');
                            $menu->route([
                                'name' => 'admin.system.user',
                                'params' => []
                            ], __('User'), '', [], 'admin.system.user');
                            $menu->route(
                                ['name' => 'admin.system.role', 'params' => []],
                                __('Role'),
                                '',
                                [],
                                'admin.system.role'
                            );
                            $menu->route([
                                'name' => 'admin.system.permission',
                                'params' => []
                            ], __('Permission'), '', [], 'admin.system.permission');
                        }, 9999999999999)
                        ->subMenu(__('Appearance'), '<i class="ti ti-brush fs-2"></i>', function (MenuBuilder $menu) {
                            $menu->setTargetId('system_appearance_menu');
                            $menu->route(
                                [
                                    'name' => 'admin.extension.theme',
                                    'params' => []
                                ],
                                'Theme',
                                '',
                                [],
                                'admin.extension.theme'
                            );
                            if (themeOption()->checkOptionUI()) {
                                $menu->route([
                                    'name' => 'admin.extension.theme.option',
                                    'params' => []
                                ], 'Theme Option', '', [], 'admin.extension.theme.option');
                            }
                            if (Theme::SiteDataInfo()) {
                                $menu->route([
                                    'name' => 'admin.extension.theme.menu',
                                    'params' => []
                                ], 'Menu', '', [], 'admin.extension.theme.menu');
                            }
                        }, 9999999999999)
                        ->route(
                            ['name' => 'admin.extension.module', 'params' => []],
                            __('Module'),
                            '<i class="ti ti-package fs-2"></i>',
                            [],
                            '',
                            9999999999999
                        )
                        ->subMenu('Settings', '<i class="ti ti-settings fs-2"></i>', function (MenuBuilder $menu) {
                            $menu->setTargetId('system_setting_menu');
                            $menu->route('admin.setting.general', 'System', '', [], 'admin.setting.general');
                            $menu->route(
                                'admin.system.clean-system-tool',
                                'System Tool ',
                                '',
                                [],
                                'admin.system.clean-system-tool'
                            );
                            $menu->route(
                                'admin.system.cookies-setting',
                                __('Cookie Banner'),
                                '',
                                [],
                                'admin.system.cookies-setting'
                            );
                        }, 99999999999999)
                        ->subMenu(__('System'), '<i class="ti ti-assembly fs-2"></i>', function (MenuBuilder $menu) {
                            $menu->setTargetId('system_menu');

                            $menu->route(
                                'admin.system.updater',
                                __('System Updater'),
                                '',
                                [],
                                ''
                            );
                            $menu->route(
                                'admin.system.license',
                                __('Your License'),
                                '',
                                [],
                                ''
                            );
                            $menu->route(
                                'admin.system.log-viewer',
                                __('Log Viewer'),
                                '',
                                [],
                                'admin.system.log-viewer'
                            );
                            $menu->route(
                                'admin.system.permalink-setting',
                                __('Permalink'),
                                '',
                                [],
                                'admin.system.permalink-setting'
                            );
                        }, 9999999999999999);
                });
            }
        });
    }
}
