<?php

namespace Sokeio\UI\Common\Concerns;

trait WithTextHtml
{
    public function xHtml($value)
    {
        return $this->render(fn($base) => $base->attr('x-html', '$wire.' . $base->getNameWithPrefix($value)));
    }
    public function xText($value)
    {
        return $this->render(fn($base) => $base->attr('x-text', '$wire.' . $base->getNameWithPrefix($value)));
    }
}
