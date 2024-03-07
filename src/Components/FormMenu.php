<?php

namespace Sokeio\Components;

use Sokeio\Menu\MenuItemBuilder;
use Sokeio\Models\Menu;

class FormMenu extends Form
{
    public  static function getMenuType(): string
    {
        echo 'getMenuType';
        return '';
    }
    public static function getMenuName(): string
    {
        echo 'getMenuName';
        return '';
    }
    public static function renderItem(MenuItemBuilder $item)
    {
        echo 'renderItem';
    }
    protected function getModel(): string
    {
        return Menu::class;
    }
    protected function DefaultUI()
    {
        return [
            UI::row([
                UI::column(UI::icon('icon')->label(__('Icon'))),
                UI::column(UI::color('color')->label(__('Color'))),
            ]),
            UI::text('name')->label(__('Name'))->required(),
            UI::textarea('info')->label(__('Info')),
            UI::text('attr_name')->label(__('Attribute')),
            UI::text('class_name')->label(__('Class name')),
            UI::hidden('data_type')->valueDefault(function () {
                return static::getMenuType();
            })
        ];
    }
    protected function MenuUI()
    {
        return [];
    }
    protected function formUI()
    {
        return UI::prex('data', [...$this->DefaultUI(), ...$this->MenuUI()]);
    }
    protected function footerUI()
    {
        if ($this->isEdit()) {
            return [
                UI::Div([
                    UI::button(__('Save'))->wireClick('doSave()')
                ])->className('p-2 text-center')
            ];
        } else {
            return [
                UI::Div([
                    UI::button(__('Add to menu'))->attribute(' @click="doAddMenu()" ')
                ])->className('p-2 text-end')
            ];
        }
    }
    public function checkValidate()
    {
        return parent::doValidate();
    }
    protected function getFormAttribute()
    {
        return  ' x-data="{
            async doAddMenu() {
                let flg = await this.$wire.checkValidate();
                if(!flg) return;
                if ($elManager = this.$wire.$el.closest(\'.menu-parent-manager\')) {
                    await (Alpine.$data($elManager)).addMenuItem({ ...$wire.data });
                    $wire.data = {};
                }
            }
        }" wire:ignore.self ';
    }
}
