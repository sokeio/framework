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
    public function saveData()
    {
        $data =  $this->dataId ? ($this->getModel())::find($this->dataId) : new ($this->getModel())();
        $this->formData->parseModel($data, $this->formData->keys());
        $data->save();
        $this->refreshRef();
        $this->sokeioClose();
    }
}
