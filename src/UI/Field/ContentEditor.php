<?php

namespace Sokeio\UI\Field;


class ContentEditor extends FieldUI
{
    private static $editorDefault = 'sokeio::tinymce';
    private static $editors = [
        'sokeio::tinymce' => [
            'label' => 'Tinymce',
            'key' => 'wire:tinymce',
            'attrs' => [
                'wire:tinymce',
            ],
            'options' => []
        ]
    ];
    public static function registerEditor($key, $value)
    {
        if (!$key || !$value) {
            return;
        }
        self::$editors[$key] = $value ?? [
            'attrs' => [],
            'options' => []
        ];
    }
    private function getEditor()
    {
        $key = setting('sokeio::content-editor', self::$editorDefault);
        if (!isset(static::$editors[$key])) {
            $key = self::$editorDefault;
        }
        return static::$editors[$key];
    }
    protected function initUI()
    {
        parent::initUI();
        $this->render(function () {
            $editor = $this->getEditor();
            foreach ($editor['attrs'] as $key => $value) {
                if (! is_string($key)) {
                    $this->attr($value);
                    continue;
                }
                $this->attr($key, $value);
            }
            $this->attr('class', 'form-control');
        });
    }
    protected function fieldView()
    {
        $attr = $this->getAttr();
        return <<<HTML
         <textarea {$attr}></textarea>
        HTML;
    }
}
