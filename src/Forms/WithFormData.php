<?php

namespace BytePlatform\Forms;

use BytePlatform\ItemForm;
use BytePlatform\Traits\WithItemManager;

trait WithFormData
{
    use WithItemManager;
    public $dataId;
    public ItemForm $form;
    public function getDataId()
    {
        if ($this->dataId) {
            return  $this->dataId;
        }
        return $this->getItemManager()->getDataDefaultId();
    }

    public function Items()
    {
        return $this->getItemManager()?->getItems();
    }

    public function mount()
    {
        $this->form->DataToForm();
    }
    public function doSave()
    {
        $this->getItemManager()?->callDoAction('FORM_DO_SAVE', [], $this);
    }
    protected function getView()
    {
        return 'byte::forms.index';
    }
    public function render()
    {
        page_title($this->getItemManager()?->getTitle());
        return view($this->getView(), [
            'itemManager' => $this->getItemManager()
        ]);
    }
}
