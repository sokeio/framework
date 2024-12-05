<?php

namespace Sokeio\UI\Field;

use Sokeio\UI\Field\Concerns\WithDatasource;

class CheckboxList extends FieldUI
{
    use WithDatasource;
    public function showDebug()
    {
        return $this->vars('showDebug', true);
    }
    public function labelCheckbox($label)
    {
        return $this->render(function () use ($label) {
            $this->vars('labelCheckbox', $label);
        });
    }
    protected function initUI()
    {
        parent::initUI();
        $this->render(function () {
            if (!$this->containsAttr('class', 'form-check-input')) {
                $this->className('form-check-input');
            }
            $this->attr('type', 'checkbox');
            if ($this->containsAttr('value')) {
                $this->removeAttr('value');
            }
        });
    }
    protected function checklistRender()
    {
        $html = '';
        $data = $this->getDatasource();
        $time = now()->timestamp;
        $name = $this->getFieldNameWithoutPrefix();
        $this->removeAttr('value');
        $attr = $this->getAttr();
        foreach ($data as $item) {
            $value = "{$item['value']}";
            $html .= <<<HTML
        <label class="form-check">
            <input name="{$name}[]"  value="{$value}" id="{$name}_{$time}_{$value}" {$attr}/>

            <span for="{$name}_{$time}_{$value}"
             class="form-check-label">{$item['text']}</span>
        </label>
        HTML;
        }
        return $html;
    }
    protected function fieldView()
    {
        $attrWrapper = $this->getAttr('wrapper') ?? 'class="mb-3"';
        $classSpan = 'd-none';
        if ($this->checkVar('showDebug')) {
            $classSpan = 'd-block';
        }
        return <<<HTML
        <div {$attrWrapper} >
            <span class="{$classSpan}" x-text="JSON.stringify(FieldValue)"
            x-init="if(!Array.isArray(FieldValue)) {FieldValue = []}"></span>
            <div x-ref="checklist" class="mt-2" >
            {$this->checklistRender()}
            </div>
            <button class="btn btn-primary p-1 px-2"
            x-data="{flg: true}"
            @click="\$refs.checklist.querySelectorAll('input').forEach(function(item){item.checked = flg; item.dispatchEvent(new Event('change'));}); flg = !flg"
             type="button" x-text="flg?'Uncheck all':'Check all'" >Check all</button>
        </div>
        HTML;
    }
}
