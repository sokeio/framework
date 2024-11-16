<?php

namespace Sokeio\UI;

use Sokeio\FormData;

trait WithEditUI
{
    use WithUI;
    public function queryStringWithEditUI()
    {
        return [
            'dataId' => ['except' => null]
        ];
    }
    public FormData $formData;
    public $dataId;
    public function mount()
    {
        $data =  $this->dataId ? ($this->getModel())::find($this->dataId) : new ($this->getModel());
        $this->formData->fill($data);
    }
    protected function afterSaveData($data)
    {
        return $data;
    }
    protected function beforeSaveData($data)
    {
        return $data;
    }
    protected function getTitleForm()
    {
        return ($this->dataId ? __('Edit') : __('Create')) . ' ' . $this->getPageConfig()->getTitle();
    }
    protected function formMessage()
    {
        $this->alert(__('Saved successfully'));
    }
    public function saveData()
    {

        $this->getUI()->validate();
        $data =  $this->dataId ? ($this->getModel())::find($this->dataId) : new ($this->getModel())();
        $this->getUI()->fill($data);
        $this->beforeSaveData($data);
        $data->save();
        $this->afterSaveData($data);
        $this->formMessage();
        $this->refreshRef();
        $this->sokeioClose();
    }
}
