<?php

namespace Sokeio\Components;

use Sokeio\Menu\MenuItemBuilder;
use Sokeio\Models\Menu;

class FormMenu extends Form
{
    public static function getMenuType()
    {
        return '';
    }
    public static function getMenuName()
    {
        return '';
    }
    public static function RenderItem(MenuItemBuilder $item)
    {
    }
    protected function getModel()
    {
        return Menu::class;
    }
    protected function DefaultUI()
    {
        return [
            UI::Row([
                UI::Column(UI::Icon('icon')->Label(__('Icon'))),
                UI::Column(UI::Color('class_name')->Label(__('Color'))),
            ]),
            UI::Text('name')->Label(__('Name'))->required(),
            UI::Text('attr_name')->Label(__('Attribute')),
            UI::Text('class_name')->Label(__('Class name')),
            UI::Hidden('data_type')->ValueDefault(function () {
                return static::getMenuType();
            })
        ];
    }
    protected function MenuUI()
    {
        return [];
    }
    protected function FormUI()
    {
        return UI::Prex('data', [...$this->DefaultUI(), ...$this->MenuUI()]);
    }
    protected function FooterUI()
    {
        if ($this->isEdit()) {
            return [
                UI::Div([
                    UI::Button(__('Save'))->WireClick('doSave()')
                ])->ClassName('p-2 text-center')
            ];
        } else {
            return [
                UI::Div([
                    UI::Button(__('Add to menu'))->Attribute(' @click="doAddMenu()" ')
                ])->ClassName('p-2 text-end')
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
