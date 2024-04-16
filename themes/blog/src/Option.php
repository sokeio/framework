<?php

namespace SokeioTheme\Blog;

use Sokeio\Components\Common\Tab;
use Sokeio\Components\UI;
use Sokeio\WithOption;

class Option
{
    use WithOption;
    // Only Theme Site
    public static function setupOption()
    {
        // Run when activating
        themeOption()->optionUI([
            UI::tab()
                ->addTab(Tab::TabItem('General'), [
                    UI::row([
                        UI::column6([
                            UI::text('site_name')->label(__('Site Name')),
                        ]),
                        UI::column6([
                            UI::image('site_logo')->label(__('Site Logo')),
                        ])
                    ])

                ])->addTab(Tab::TabItem('Footer'), [
                    UI::color('footer_color')->label(__('Color Footer')),
                    UI::row([
                        UI::column3([
                            UI::text('footer_column_title1')->label(__('Footer column 1')),
                        ]),
                        UI::column3([
                            UI::text('footer_column_title2')->label(__('Footer column 2')),
                        ]),
                        UI::column3([
                            UI::text('footer_column_title3')->label(__('Footer column 3')),
                        ]),
                        UI::column3([
                            UI::text('footer_column_title4')->label(__('Footer column 4')),
                        ]),
                    ]),
                    UI::tinymce('footer_about')->label(__('Footer About')),

                ])->addTab(Tab::TabItem('Header'), [
                    UI::color('header_color')->label(__('Color Header')),
                ])->addTab(Tab::TabItem('Customize'), [
                    UI::textarea('custom_css')->label(__('Custom CSS')),
                    UI::textarea('custom_js')->label(__('Custom JS')),
                ]),

        ]);
    }
    public static function activate()
    {
        // Run when activating
    }

    public static function activated()
    {
        // Run when is activated
    }

    public static function deactivate()
    {
        // Run when deactivating 
    }

    public static function deactivated()
    {
        // Run when is deactivated
    }

    public static function remove()
    {
        // Run when remove 
    }

    public static function removed()
    {
        // Run when removed
    }
}
