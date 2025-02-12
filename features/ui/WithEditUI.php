<?php

namespace Sokeio\UI;

use Illuminate\Support\Facades\DB;
use Sokeio\Core\Attribute\ModelInfo;
use Sokeio\FormData;
use Sokeio\Platform;

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
        DB::transaction(function () use ($data) {
            $this->beforeSaveData($data);
            $model =  ModelInfo::fromClass($this->getModel());
            if ($model && !$model->skipBy) {
                if ($this->dataId) {
                    $data->created_by = Platform::gate()->getUserId();
                } else {
                    $data->updated_by = Platform::gate()->getUserId();
                }
            }

            $data->save();
            $this->afterSaveData($data);
            $this->formMessage();
            $this->refreshRef();
            $this->sokeioClose();
        });
    }
}
