<?php

namespace Sokeio\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class PlatformController extends BaseController
{
    public function doEvents(Request $request)
    {
        $event_name = $request->get('event');
        if ($event_name) {
            return apply_filters(PLATFORM_DO_EVENT . '_' . Str::upper($event_name), ['request' => $request]);
        }
        return  apply_filters(PLATFORM_DO_EVENT, ['request' => $request]);
    }
    public function doWebhooks(Request $request)
    {
        $webhook_name = $request->get('webhook');
        if ($webhook_name) {
            return apply_filters(PLATFORM_DO_WEBHOOK . '_' . Str::upper($webhook_name), ['request' => $request]);
        }

        return  apply_filters(PLATFORM_DO_WEBHOOK, ['request' => $request]);
    }
}
