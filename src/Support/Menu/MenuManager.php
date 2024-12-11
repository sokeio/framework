<?php

namespace Sokeio\Support\Menu;

use Sokeio\Pattern\Singleton;

class MenuManager
{
    use Singleton;
    /** @var MenuCollection[] */
    private $menuPositions = [];
    public function target($target, $callback, $position = 'default')
    {
        $this->getPosition($position)->target($target, $callback);
        return $this;
    }
    public function register($menu, $position = 'default')
    {
        $this->getPosition($position)->register($menu);
        return $this;
    }
    public function getPosition($position = 'default'): MenuCollection
    {
        return $this->menuPositions[$position] ??= new MenuCollection($this);
    }
    public function render($position = 'default')
    {
        return $this->getPosition($position)->render();
    }
}
