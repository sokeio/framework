<?php

namespace Sokeio\UI\Common\Concerns;

trait WithTextHtml
{
    public function xHtml($value)
    {
        return $this->render(fn() => $this->attr('x-html', '$wire.' . $this->getNameWithPrefix($value)));
    }
    public function xText($value)
    {
        return $this->render(fn() => $this->attr('x-text', '$wire.' . $this->getNameWithPrefix($value)));
    }
}
