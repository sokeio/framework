<?php

namespace Sokeio\Platform\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Sokeio\WatchTime;

trait WithPlatformCallback
{

    private $readyCallbackByKey = [];
    private function readyByKey($key, $callback = null, $debug = false)
    {
        if (!isset($this->readyCallbackByKey[$key])) {
            $this->readyCallbackByKey[$key] = [];
        }
        if ($callback && is_callable($callback)) {
            $this->readyCallbackByKey[$key][] = [
                'callback' => $callback,
                'debug' => $debug
            ];
        }
    }
    private function doReadyByKey($key)
    {
        if (!isset($this->readyCallbackByKey[$key]) || count($this->readyCallbackByKey[$key]) < 1) {
            return;
        }
        foreach ($this->readyCallbackByKey[$key] as  $callback) {

            if ($callback['debug']) {
                WatchTime::start();
                Log::debug(
                    $key . "::start",
                    ['file' => $callback['debug']['file'], 'line' => $callback['debug']['line']]
                );
            }
            $callback['callback']();
            if ($callback['debug']) {
                WatchTime::logTime(true, $key);
                Log::debug(
                    $key . "::end ",
                    ['file' => $callback['debug']['file'], 'line' => $callback['debug']['line']]
                );
            }
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
        // $tracking = debug_backtrace()[1];
//, ['file' => $tracking['file'], 'line' => $tracking['line']
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
