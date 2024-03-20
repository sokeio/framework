<?php

namespace Sokeio\Icon;

use Sokeio\Facades\Assets;

class TablerIcon extends IconBase
{
    public function addToAsset()
    {
        Assets::AddCss('tabler-icons/tabler-icons.css', 'module', 'sokeio');
    }
    public function getKey()
    {
        return 'TablerIcon';
    }
    public function getName()
    {
        return 'Tabler Icon';
    }
    public function getPre()
    {
        return 'ti';
    }
    public function getPathIcon()
    {
        return __DIR__ . '/../../public/tabler-icons/tabler-icons.css';
    }
}
