<?php

namespace Sokeio\UI;

use Sokeio\FormData;

trait WithEditUI
{
    use WithUI;
    private $dataModel = null;
    public function getDataModel()
    {
        if (!$this->dataModel) {
            $this->dataModel =  $this->dataId ? ($this->getModel())::find($this->dataId) : new ($this->getModel())();
            $this->getUI()->fill($this->dataModel);
        }
        return $this->dataModel;
    }
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
        $this->dataModel =  $this->dataId ? ($this->getModel())::find($this->dataId) : new ($this->getModel());
        $this->formData->fill($this->dataModel);
        $this->afterMount($this->dataModel);
    }
    protected function afterMount($data)
    {
        return $data;
    }
    protected function afterSaveData($data)
    {
        return $data;
    }
    protected function beforeSaveData($data)
    {
        return $data;
    }
    public function getPageTitle()
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
        $data =  $this->getDataModel();
        $this->beforeSaveData($data);
        $data->save();
        $this->afterSaveData($data);
        $this->formMessage();
        $this->refreshRef();
        $this->sokeioClose();
    }
}
