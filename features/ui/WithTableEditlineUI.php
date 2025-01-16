<?php

namespace Sokeio\UI;

use Illuminate\Support\Facades\DB;
use Sokeio\Platform;

trait WithTableEditlineUI
{
    use WithUI;
    public $tableEditline = [];
    public $tableRowEditline = [];
    protected function mapRowKey($id)
    {
        return 'row_' . $id;
    }
    public function rowEditline($id)
    {
        if (!in_array($id, $this->tableRowEditline)) {
            $this->tableRowEditline[] = "$id";
        }
        if (!in_array($id, $this->dataSelecteds)) {
            $this->dataSelecteds[] = "$id";
        }
    }
    public function cancelRowEditline($id)
    {
        unset($this->tableEditline[$this->mapRowKey($id)]);
        $this->dataSelecteds = collect($this->dataSelecteds)->filter(function ($item) use ($id) {
            return $item !== "$id";
        })->toArray();
        $this->tableRowEditline = collect($this->tableRowEditline)->filter(function ($item) use ($id) {
            return $item !== "$id";
        })->toArray();
    }

    public function saveRowEditline($id)
    {
        if (!$id) {
            return;
        }
        $this->getUI()->validate('tableEditline', 'tableColumnEdit', true, $this->mapRowKey($id));
        $this->fillModelAndSaveById($id);
        $this->cancelRowEditline($id);
        $this->alert('Record has been saved!', 'Saved', 'success');
    }
    protected function fillModelAndSaveById($id)
    {
        $dataModel =  $id ? ($this->getModel())::find($id) : new ($this->getModel())();
        $this->getUI()->fillWithId($dataModel, 'tableEditline', 'tableColumnEdit', $this->mapRowKey($id));
        $this->beforeSaveData($dataModel);
        if (!isset($this->skipByUser) || !$this->skipByUser) {
            if ($id) {
                $dataModel->created_by = Platform::gate()->getUserId();
            } else {
                $dataModel->updated_by = Platform::gate()->getUserId();
            }
        }
        $dataModel->save();
        $this->afterSaveData($dataModel);
    }
    protected function afterSaveData($data)
    {
        return $data;
    }
    protected function beforeSaveData($data)
    {
        return $data;
    }
    protected function formMessage()
    {
        $this->alert(__('Saved all records'), 'Saved', 'success');
    }
    public function saveData()
    {
        $rowIds = collect($this->tableRowEditline)->filter(fn($id) => in_array($id, $this->dataSelecteds));
        $this->getUI()->validate(
            'tableEditline',
            'tableColumnEdit',
            true,
            $rowIds
                ->map(fn($id) => $this->mapRowKey($id))
                ->toArray()
        );
        DB::transaction(function () use ($rowIds) {
            foreach ($rowIds as $id) {
                $this->fillModelAndSaveById($id);
                $this->cancelRowEditline($id);
            }
            $this->formMessage();
        });
    }
}
