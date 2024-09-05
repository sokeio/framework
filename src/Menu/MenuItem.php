<?php

namespace Sokeio\Menu;


class MenuItem
{
    protected MenuManager $menuManager;

    public function setManager(MenuManager $menuManager)
    {
        $this->menuManager = $menuManager;
    }
}
