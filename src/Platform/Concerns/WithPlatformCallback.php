<?php

namespace Sokeio\Platform\Concerns;

use Illuminate\Support\Facades\File;

trait WithPlatformCallback
{

    private $readyCallbackByKey = [];
    private function readyByKey($key, $callback = null)
    {
        if (!isset($this->readyCallbackByKey[$key])) {
            $this->readyCallbackByKey[$key] = [];
        }
        if ($callback && is_callable($callback)) {
            $this->readyCallbackByKey[$key][] = $callback;
        }
    }
    private function doReadyByKey($key)
    {
        if (!isset($this->readyCallbackByKey[$key]) || count($this->readyCallbackByKey[$key]) < 1) {
            return;
        }
        foreach ($this->readyCallbackByKey[$key] as  $callback) {
            $callback();
        }
    }
    public function routeAdminBeforeReady($callback = null)
    {
        $this->readyByKey('route_admin', $callback);
    }
    public function doRouteAdminBeforeReady()
    {
        $this->doReadyByKey('route_admin');
    }
    public function routeSiteBeforeReady($callback = null)
    {
        $this->readyByKey('route_site', $callback);
    }
    public function doRouteSiteBeforeReady()
    {
        $this->doReadyByKey('route_site');
    }
    public function routeApiBeforeReady($callback = null)
    {
        $this->readyByKey('route_api', $callback);
    }
    public function doRouteApiBeforeReady()
    {
        $this->doReadyByKey('route_api');
    }
    public function ready($callback = null)
    {
        $this->readyByKey('platform', $callback);
    }
    public function doReady()
    {
        $this->doReadyByKey('platform');
    }
    public function readyAfter($callback = null)
    {
        $this->readyByKey('platform_after', $callback);
    }
    public function doReadyAfter()
    {
        $this->doReadyByKey('platform_after');
    }
}
