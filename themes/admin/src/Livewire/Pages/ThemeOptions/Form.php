<?php

namespace ByteTheme\Admin\Livewire\Pages\ThemeOptions;

use BytePlatform\Component;
use BytePlatform\Facades\Theme;
use BytePlatform\ItemForm;
use BytePlatform\Traits\WithItemManager;

class Form extends Component
{
    use WithItemManager;
    protected function ItemManager()
    {
        if ($this->tabActive && ($theme = Theme::SiteDataInfo()))
            return $theme->getOptionDataHook()->getFormByKey($this->tabActive);
        return null;
    }
    public ItemForm $form;

    public $tabActive;

    public function mount()
    {
        $this->BindData();
    }
    public function BindData()
    {
        $arr = [];
        foreach ($this->getItemManager()->getItems() as $item) {
            $arr[$item->getField()] = setting($item->getField());
        }
        $this->form->DataToForm($arr);
    }
    public function saveSetting()
    {
        $this->form->CheckValidate();
        foreach ($this->form->toArray() as $key => $value) {
            if ($key == 'page_site_theme') {
                Theme::find($value)?->Active();
            } else {
                set_setting($key, $value);
            }
        };
        $this->showMessage('Update setting success!');
    }

    public function render()
    {
        return view('theme::pages.theme-options.form', [
            'itemManager' => $this->getItemManager()
        ]);
    }
}
