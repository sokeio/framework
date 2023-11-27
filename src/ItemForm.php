<?php

namespace Sokeio;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ItemForm extends DataForm
{
    private $__dataId = "";
    public function setDataId($id)
    {
        $this->__dataId = $id;
        return $this;
    }
    public function getDataId()
    {
        return $this->__dataId;
    }
    private $__dataModel = "";
    public function setDataModel($__dataModel)
    {
        $this->__dataModel = $__dataModel;
        return $this;
    }
    public function getDataModel()
    {
        return $this->__dataModel;
    }
    public function InitForm()
    {
        parent::InitForm();
        if (method_exists($this->getComponent(), 'getDataId')) {
            $this->setDataId($this->getComponent()->getDataId());
        } else if (isset($this->getComponent()->dataId))
            $this->setDataId($this->getComponent()->dataId);
    }
    public function rules()
    {
        $rules = [];
        if (!!$this->itemManager) {
            foreach ($this->itemManager->getItems() as $item) {
                $ruleField = [];
                if ($item->getRequired()) {
                    $ruleField[] = "required";
                }
                if ($_rules = $item->getRules()) {
                    if (is_array($_rules)) {
                        foreach ($_rules as $_item) {
                            $ruleField[] = $_item;
                        }
                    } else {
                        $ruleField[] = $_rules;
                    }
                }
                if (count($ruleField) > 0) {
                    $rules[$item->getField()] = $ruleField;
                }
            }
        }
        return $rules;
    }


    public function DataToForm($data = null)
    {
        if (!!$this->itemManager) {
            if ($this->itemManager->getNoBindData())
                return;
            $__dataId = $this->getDataId();
            if (!$data) {
                $query = $this->component->newQuery();
                if (!!$__dataId && !!$query) {
                    $data = $query->where('id', $__dataId)->first();
                }
            }
            if ($data == null) {
                foreach ($this->itemManager->getItems() as  $item) {
                    if ($item->getWhen() && !$item->getNoBindData())
                        $this->{$item->getField()} =  $item->getValueDefault();
                }
            } else if (is_array($data)) {
                foreach ($this->itemManager->getItems() as  $item) {
                    if ($item->getWhen() && !$item->getNoBindData())
                        $this->{$item->getField()} = isset($data[$item->getField()]) ? $data[$item->getField()] : $item->getValueDefault();
                }
            } else {
                foreach ($this->itemManager->getItems() as  $item) {
                    if ($item->getWhen())
                        if (!$item->getNoBindData())
                            $this->{$item->getField()} = isset($data->{$item->getField()}) ? $data->{$item->getField()} : $item->getValueDefault();
                        else if ($callback = $item->getFieldData()) {
                            $this->{$item->getField()} = $callback($data, $item, $this->itemManager);
                        }
                }
            }
        }
    }
    public function CheckValidate()
    {
        $rules = $this->rules();
        if (count($rules) > 0) {
            $validator = Validator::make($this->toArray(), $rules);
            $validator->after(function (\Illuminate\Validation\Validator $validator) {
                $keys = $validator->errors()->keys();
                foreach ($keys as $key) {
                    $value = $validator->errors()->messages()[$key];
                    $validator->errors()->add($this->getPropertyName() . '.' . $key, $value);
                    $validator->errors()->forget($key);
                }
            });
            $validator->validate();
        }
    }
    public function DataFromForm()
    {
        if (!!$this->itemManager) {
            $this->CheckValidate();
            $__dataId = $this->getDataId();
            $query = $this->component->newQuery();
            if ($query) {
                $dataModel =  $query->where('id', $__dataId)->first();
                if (!$dataModel)  $dataModel = $this->component->newModel();
                foreach ($this->itemManager->getItems() as $item) {
                    if ($item->getEdit() && $item->getWhen() && !$item->getNoBindData()) {
                        $dataModel->{$item->getField()} = $this->{$item->getField()} ? $this->{$item->getField()} : $item->getValueDefault();
                    }
                }
                DB::transaction(function () use ($dataModel) {
                    if (method_exists($this->itemManager, 'getBeforeSave'))
                        $dataModel = $this->itemManager->getBeforeSave($dataModel);
                    $dataModel->save();
                    if (method_exists($this->itemManager, 'getAfterSave'))
                        $dataModel = $this->itemManager->getAfterSave($dataModel);
                });

                return $dataModel;
            }
        }
        return null;
    }
}
