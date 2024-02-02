<?php

namespace Sokeio\Icon;

use Sokeio\Facades\Assets;

class BootstrapIcon extends IconBase
{
    public function AddToAsset()
    {
        Assets::AddCss('bootstrap-icons/bootstrap-icons.css', 'module', 'sokeio');
    }
    public function getKey()
    {
        return 'BootstrapIcon';
    }
    public function getName()
    {
        return 'Bootstrap Icon';
    }
    public function getPre()
    {
        return 'bi';
    }
    public function getPathIcon()
    {
        return __DIR__ . '/../../public/bootstrap-icons/bootstrap-icons.css';
    }
}
