<?php

namespace Sokeio\Components;

use Sokeio\Models\Menu;

class FormMenu extends Form
{
    protected function getModel()
    {
        return Menu::class;
    }
    protected function DefaultUI()
    {
        return [
            UI::Icon('icon')->Label(__('Icon')),
            UI::Text('name')->Label(__('Name'))->required(),
            UI::Text('attr_name')->Label(__('Attribute')),
            UI::Text('class_name')->Label(__('Class name')),
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
