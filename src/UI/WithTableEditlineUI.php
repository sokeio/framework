<?php

namespace Sokeio\UI;

use Sokeio\FormData;

trait WithTableEditlineUI
{
    use WithUI;
    public FormData $tableEditline;

    protected function afterSaveData($data)
    {
        return $data;
    }
    protected function beforeSaveData($data)
    {
        return $data;
    }

    public function saveData()
    {
        $this->getUI()->validate();
        // $data =  $this->getDataModel();
        // DB::transaction(function () use ($data) {
        //     $this->beforeSaveData($data);
        //     if (!isset($this->skipByUser) || !$this->skipByUser) {
        //         if ($this->dataId) {
        //             $data->created_by = Platform::gate()->getUserId();
        //         } else {
        //             $data->updated_by = Platform::gate()->getUserId();
        //         }
        //     }

        //     $data->save();
        //     $this->afterSaveData($data);
        //     $this->formMessage();
        //     $this->refreshRef();
        //     $this->sokeioClose();
        // });
    }
}
