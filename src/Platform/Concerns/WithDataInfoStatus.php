<?php

namespace Sokeio\Platform\Concerns;

use Illuminate\Support\Facades\Log;
use Sokeio\Events\PlatformChanged;
use Sokeio\Facades\Platform;

trait WithDataInfoStatus
{
    public const ACTIVE = 1;
    public const BLOCK = 0;
    public function isVendor()
    {
        return checkPathVendor($this->getPath(), $this['baseType']);
    }

    public function setStatusData($value)
    {
        if ($value === self::ACTIVE) {
            $this->TriggerEvent('activate');
            $this->getBase()->active($this->getId());
            $this->TriggerEvent('activated');
        } else {
            $this->TriggerEvent('deactivate');
            $this->getBase()->block($this->getId());
            $this->TriggerEvent('deactivated');
        }
        ob_start();
        PlatformChanged::dispatch($this);
        Platform::makeLink();
        runCmd(base_path(''), 'php artisan migrate');
        Log::info(ob_get_clean());
    }
    public function isActive()
    {
        return $this->status == self::ACTIVE;
    }
    public function isActiveOrVendor()
    {
        return $this->isActive() || $this->isVendor();
    }
    public function active()
    {
        $this->status = self::ACTIVE;
    }
    public function block()
    {
        $this->status = self::BLOCK;
    }
}
