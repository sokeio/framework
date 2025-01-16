<?php

namespace Sokeio\Theme;


class TemplateManager
{
    private $templates = [];
    public function register($template, $content)
    {
        $this->templates[$template] = [
            'content' => $content,
            'title'=>__($template),
            'template' => $template
        ];
        return $this;
    }
    public function getTemplates()
    {
        return $this->templates;
    }
}
