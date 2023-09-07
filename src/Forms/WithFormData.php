<?php

namespace BytePlatform\Forms;

use BytePlatform\ItemForm;
use BytePlatform\Traits\WithItemManager;

trait WithFormData
{
    use WithItemManager;
    public $dataId;
    public ItemForm $form;


    public function Items()
    {
        return $this->getItemManager()->getItems();
    }

    public function mount()
    {
        $this->form->DataToForm();
    }
    public function doSave()
    {
        $this->getItemManager()->callDoAction('FORM_DO_SAVE', [], $this);
    }
    public function render()
    {
        page_title($this->getItemManager()->getTitle());
        return view('byte::forms.index', [
            'itemManager' => $this->getItemManager()
        ]);
    }
}
