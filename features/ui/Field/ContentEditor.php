<?php

namespace Sokeio\UI\Field;


class ContentEditor extends FieldUI
{
    private static $editorDefault = 'sokeio::tinymce';
    private static $editors = [
        'sokeio::tinymce' => [
            'label' => 'Tinymce',
            'key' => 'wire:tinymce',
            'attrs' => [],
            'options' => [
                'height' => 500
            ]
        ]
    ];
    private static $editorOptions = [];
    public static function registerEditorOptions($key, $callback)
    {
        self::$editorOptions[$key][] = $callback;
    }
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
        $options = [];
        foreach (static::$editorOptions[$key] ?? [] as $callback) {
            $options = array_merge($options, $callback($options));
        }
        return [
            'key' => self::$editors[$key]['key'],
            'options' => $options,
            'attrs' => self::$editors[$key]['attrs']
        ];
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
            $this->attr($editor['key']);
            $this->attr($editor['key'] . '.options', json_encode($editor['options']));
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
