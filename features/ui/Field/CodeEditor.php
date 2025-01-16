<?php

namespace Sokeio\UI\Field;


class CodeEditor extends FieldUI
{
    public function language($language)
    {
        $this->attr('wire:code-editor.language', $language);
        return $this;
    }
    protected function initUI()
    {
        parent::initUI();
        return $this->render(function () {
            $this->attr('wire:code-editor');
            $this->className('sokeio-code-editor');
        });
    }
    protected function fieldView()
    {
        $attr = $this->getAttr('default', function ($key, $value) {

            if (is_array($value)) {
                if ($key === "class") {
                    $value = array_filter($value, function ($v) {
                        return $v !== 'form-control';
                    });
                }
                $value = implode(' ', $value);
            }
            return $key . '="' . $value . '"';
        });

        return <<<HTML
         <div wire:ignore {$attr}></div>
        HTML;
    }
}
