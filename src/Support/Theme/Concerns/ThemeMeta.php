<?php

namespace Sokeio\Support\Theme\Concerns;

trait ThemeMeta
{
    private $arrMeta = [
        'title' => '',
        'description' => '',
        'keywords' => '',
    ];
    private $skipMetaRender = false;
    public function skipMetaRender()
    {
        $this->skipMetaRender = true;
        return $this;
    }
    public function getSiteInfo()
    {
        return $this->arrMeta;
    }
    public function meta($key, $value)
    {
        $this->arrMeta[$key] = $value;
        return $this;
    }
    public function title($title)
    {
        return $this->meta('title', $title);
    }
    public function description($description)
    {
        return $this->meta('description', $description);
    }
    public function keywords($keywords)
    {
        return $this->meta('keywords', $keywords);
    }
    public function metaRender()
    {
        if ($this->skipMetaRender) {
            return;
        }
        if ($title = data_get($this->arrMeta, 'title')) {
            echo "<title>{$title}</title>";
        }
        if ($description = data_get($this->arrMeta, 'description')) {
            echo "<meta name='description' content='{$description}'>";
        }
        if ($keywords = data_get($this->arrMeta, 'keywords')) {
            if (strpos($keywords, ',') !== false) {
                $keywords = explode(',', $keywords);
                $keywords = array_map('trim', $keywords);
                $keywords = array_filter($keywords);
                $keywords = implode(',', $keywords);
            }
            echo "<meta name='keywords' content='{$keywords}'>";
        }
    }
}
