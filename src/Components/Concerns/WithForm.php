<?php

namespace Sokeio\Components\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Sokeio\Components\UI;
use Sokeio\Form;

trait WithForm
{
    use WithModelQuery;
    use WithLayoutUI;
    public $dataId;
    public $copyId;
    public $localeRef;
    public Form $data;
    public Form $dataRelations;
    protected $layout;
    protected $footer;
    protected $skipClose = false;

    protected function isEdit()
    {
        return $this->dataId != null;
    }
    protected function formMessage($isNew)
    {
        if ($isNew) {
            return __('New record created successfully');
        }
        return __('The record updated successfully');
    }
    protected function getDataRow()
    {
        $query = $this->getQuery();
        if ($this->dataId) {
            $query =  $query->where('id', $this->dataId);
            return $query->first();
        } elseif ($this->copyId) {
            $query =  $query->where('id', $this->copyId);
            return $query->first();
        }
    }
    public function loadData()
    {
        if ($this->dataId || $this->copyId) {
            $data = $this->getDataRow();
            Log::info(['data' => $data, 'dataId' => $this->dataId, 'copyId' => $this->copyId]);
            if (!$data && $this->dataId) {
                return abort(404);
            }
            if ($data && $this->localeRef && method_exists($data, 'setDefaultLocale')) {
                $data->setDefaultLocale($this->localeRef);
            }
            if (method_exists($this, 'loadDataBefore')) {
                call_user_func([$this, 'loadDataBefore'], $data);
            }
            $this->fillData($data);
            if (method_exists($this, 'loadDataAfter')) {
                call_user_func([$this, 'loadDataAfter'], $data);
            }
        }
        $this->loadDefault();
    }
    protected function fillData($data)
    {
        foreach ($this->getAllInputUI() as $column) {
            if (!$column->getNoSave()) {
                $value = data_get($data, $column->getNameEncode(), $column->getValueDefault());
                if ($column->isSyncRelations()) {
                    $arr = $data->{$column->getNameEncode()};
                    if ($arr) {
                        $value = $arr->map(function ($item) {
                            return $item->id;
                        });
                    } else {
                        $value = [];
                    }
                    if (count($value) == 0) {
                        $value = [0];
                    }
                }
                data_set($this, $column->getFormFieldEncode(), $value);
            }
        }
    }
    protected function loadDefault()
    {
        //set default value
        foreach ($this->getAllInputUI() as $column) {
            if (
                data_get($this, $column->getFormFieldEncode()) === null &&
                $column->getValueDefault() != null
            ) {
                if ($column->isSyncRelations()) {
                    data_set($this, $column->getFormFieldEncode(), $column->getValueDefault() ?? [-1, -2]);
                } else {
                    data_set($this, $column->getFormFieldEncode(), $column->getValueDefault());
                }
            }
        }
    }

    protected function getView()
    {
        if ($this->currentIsPage()) {
            $this->doBreadcrumb();
            return 'sokeio::components.form.page';
        }
        return 'sokeio::components.form.index';
    }
    protected function formUI()
    {
        return null;
    }
    protected function getFormClass()
    {
        return null;
    }
    protected function getFormAttribute()
    {
        return null;
    }
    protected function footerUI()
    {
        return [
            UI::Div([
                UI::button(__('Save'))->wireClick('doSave()')
            ])->className('p-2 text-center')
        ];
    }
    protected function formRules()
    {
        $rules = [];
        $messages = [];
        $attributes = [];
        foreach ($this->getAllInputUI() as $column) {
            if ($column->checkRule()) {
                $rules[$column->getFormField()] = $column->getRules();
                $attributes[$column->getFormField()] = $column->getLabel();
            }
        }
        return  ['rules' => $rules, 'messages' => $messages, 'attributes' => $attributes];
    }
    protected function doValidate()
    {
        ['rules' => $rules, 'messages' => $messages, 'attributes' => $attributes] = $this->formRules();

        if ($rules && count($rules)) {
            $this->withValidator(function ($validator) {
                if ($validator->fails() && method_exists($this, 'validateFail')) {
                    call_user_func([$this, 'validateFail']);
                }
            })->validate($rules, $messages, $attributes);
        }
        return true;
    }
    protected function getDataObject()
    {
        $objData = new ($this->getModel());
        if ($this->dataId) {
            $query = $this->getQuery();
            $query =  $query->where('id', $this->dataId);
            $objData = $query->first();
            if (!$objData) {
                $objData = new ($this->getModel());
            }
        }
        if ($this->localeRef && method_exists($objData, 'setDefaultLocale')) {
            $objData->setDefaultLocale($this->localeRef);
        }
        return $objData;
    }
    protected function doSaveByUser($objData, $isNew)
    {
        if ($isNew) {
            $objData->created_by = auth()->user()->id;
        } else {
            $objData->updated_by = auth()->user()->id;
        }
    }
    protected function fillToModel($objData)
    {
        foreach ($this->getAllInputUI() as $column) {
            if (!$column->getNoSave() && !$column->isSyncRelations()) {
                $value = data_get($this, $column->getFormFieldEncode(), $column->getValueDefault());
                if ($value === '' && $column->getConvertEmptyStringsToNull()) {
                    $value = null;
                }
                data_set($objData, $column->getNameEncode(), $value);
            }
        }
        return $objData;
    }
    private function syncRelations($objData)
    {
        try {
            foreach ($this->getAllInputUI() as $column) {
                if (!$column->getNoSave() && $column->isSyncRelations()) {
                    $value = data_get($this, $column->getFormFieldEncode(), $column->getValueDefault());
                    if ($value === '' && $column->getConvertEmptyStringsToNull()) {
                        $value = null;
                    }
                    $value = collect($value ?? [])
                        ->map(function ($item) {
                            return (int)$item;
                        })
                        ->filter(function ($item) {
                            return $item > 0;
                        })
                        ->toArray();
                    call_user_func([$objData, $column->getNameEncode()])->sync($value);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
    public function doSave()
    {
        if (!$this->doValidate()) {
            if (method_exists($this, 'validateFail')) {
                call_user_func([$this, 'validateFail']);
            }
            return;
        }
        $objData = $this->getDataObject();
        $isNew =  !$this->dataId;
        DB::transaction(function () use ($objData) {
            if (method_exists($this, 'fillDataBefore')) {
                call_user_func([$this, 'fillDataBefore'], $objData);
            }
            $this->fillToModel($objData);
            if (method_exists($this, 'saveBefore')) {
                call_user_func([$this, 'saveBefore'], $objData);
            }
            $objData->save();
            if (method_exists($this, 'saveAfter')) {
                call_user_func([$this, 'saveAfter'], $objData);
            }
        });
        $this->syncRelations($objData);
        $this->dataId = $objData->id;
        if ($this->skipClose === false) {
            $this->showMessage($this->formMessage($isNew));
            $this->doRefreshRef();
        }
    }
    protected function doRefreshRef()
    {
        if (!$this->currentIsPage()) {
            $this->refreshRefComponent();
            $this->closeComponent();
        }
    }
    protected function initLayout()
    {
        if (!$this->layout) {
            $this->layout = $this->reLayout($this->formUI());
        }
        if (!$this->footer) {
            $this->footer = $this->reLayout($this->footerUI());
        }
    }
    public function mount()
    {
        $this->localeRef = request()->get('locale');
        if (method_exists($this, 'beforeMount')) {
            call_user_func([$this, 'beforeMount']);
        }
        $this->loadData();
        if (method_exists($this, 'afterMount')) {
            call_user_func([$this, 'afterMount']);
        }
    }
    public function render()
    {
        return view($this->getView(), [
            'title' => $this->getTitle(),
            'layout' => $this->layout,
            'footer' => $this->footer,
            'formUIClass' => $this->getFormClass(),
            'formUIAttribute' => $this->getFormAttribute(),
        ]);
    }
}
