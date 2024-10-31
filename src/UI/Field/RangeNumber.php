<?php

namespace Sokeio\UI\Field;


class RangeNumber extends FieldUI
{
    public function range($min = 0, $max = 100, $step = 5)
    {
        return $this->attr('min', $min)->attr('max', $max)->attr('step', $step);
    }
    public function fieldName($name)
    {
        return $this->vars('name', $name);
    }

    public function initUI()
    {
        parent::initUI();
        $this->render(function () {
            if (!$this->checkAttr('min') || !$this->checkAttr('max') || !$this->checkAttr('step')) {
                $this->range(0, 100, 5);
            }
            return $this->attr('type', 'range')->className('form-range');
        });
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        return <<<HTML
            <div class="range-wrapper"
             x-data="{min:{$this->getVar('min', 0, true)}, max:{$this->getVar('max', 100, true)}}">
                <div class="range-body">
                    <div class="range-text">
                        <span class="range-min-text" x-text="min" ></span>
                        <span class="range-value"
                        :style="{left: ((FieldValue-(FieldValue>0?2:0))/(max-min)*100)+`%`}"
                        x-show="min<FieldValue && FieldValue<max"
                        x-text="FieldValue"></span>
                        <span class="range-max-text" x-text="max"></span>
                    </div>
                    <input {$attr}  />
                </div>
            </div>
        HTML;
    }
}
