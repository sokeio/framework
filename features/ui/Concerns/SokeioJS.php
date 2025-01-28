<?php

namespace Sokeio\UI\Concerns;

trait SokeioJS
{
    private $sokeioHtml = '';
    public function sokeio($main, array $components = null, $name = null)
    {
        $this->sokeioHtml = sokeioJS($name, $main, $components);
        return $this;
    }
    public function renderSokeioJS()
    {
        return $this->sokeioHtml;
    }
}
