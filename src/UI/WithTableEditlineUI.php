<?php

namespace Sokeio\UI;


trait WithTableEditlineUI
{
    use WithUI;
    public $tableEditline = [];
    public $tableRowEditline = [];
    public function rowEditline($id)
    {
        $this->tableRowEditline[$id] = $id;
        $this->dataSelecteds[] = [$id];
    }
    public function cancelRowEditline($id)
    {
        unset($this->tableRowEditline[$id]);
        unset($this->tableEditline['row_' . $id]);
        unset($this->dataSelecteds[$id]);
    }
    public function saveRowEditline($id)
    {
        $this->getUI()->validate('tableEditline', 'tableRowEditline');
    }
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
