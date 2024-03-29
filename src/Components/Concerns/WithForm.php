<?php

namespace Sokeio\Components\Concerns;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Sokeio\Components\UI;
use Sokeio\Facades\Assets;
use Sokeio\Form;

trait WithForm
{
    use WithModelQuery;
    use WithLayoutUI;
    public $dataId;
    #[Url]
    public $copyId;
    public Form $data;
    protected $layout;
    protected $footer;

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
    public function loadData()
    {
        $query = $this->getQuery();
        if ($this->dataId) {
            $query =  $query->where('id', $this->dataId);
            $data = $query->first();
            if (!$data) {
                return abort(404);
            }
            if (method_exists($this, 'loadDataBefore')) {
                call_user_func([$this, 'loadDataBefore'], $data);
            }
            $this->data->fill($data);
            if (method_exists($this, 'loadDataAfter')) {
                call_user_func([$this, 'loadDataAfter'], $data);
            }
        } elseif ($this->copyId) {
            $query =  $query->where('id', $this->copyId);
            $data = $query->first();
            if (method_exists($this, 'loadDataBefore')) {
                call_user_func([$this, 'loadDataBefore'], $data);
            }
            $this->data->fill($data);
            if (method_exists($this, 'loadDataAfter')) {
                call_user_func([$this, 'loadDataAfter'], $data);
            }
        }
        $this->loadDefault();
    }
    protected function loadDefault()
    {
        //set default value
        foreach ($this->getAllInputUI() as $column) {
            if (data_get($this, $column->getFormFieldEncode()) === null && $column->getValueDefault() != null) {
                data_set($this, $column->getFormFieldEncode(), $column->getValueDefault());
            }
        }
    }

    protected function getView()
    {
        if ($this->currentIsPage()) {
            Assets::setTitle($this->getTitle());
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
        return $objData;
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
            foreach ($this->getAllInputUI() as $column) {
                $value = data_get($this, $column->getFormFieldEncode(), $column->getValueDefault());
                data_set($objData, $column->getNameEncode(), $value);
            }
            if (method_exists($this, 'saveBefore')) {
                call_user_func([$this, 'saveBefore'], $objData);
            }
            $objData->save();
            if (method_exists($this, 'saveAfter')) {
                call_user_func([$this, 'saveAfter'], $objData);
            }
        });
        $this->dataId = $objData->id;
        $this->showMessage($this->formMessage($isNew));
        $this->doRefreshRef();
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
        $this->loadData();
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
