<?php

namespace BytePlatform\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use BytePlatform\Facades\Theme;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

class PlatformController extends BaseController
{
    public function getComponent(Request $request)
    {
        $data = byteplatform_decode($request->get('key'));
        if (isset($data['is_admin']) && $data['is_admin']) {
            add_filter(PLATFORM_IS_ADMIN, function () {
                return true;
            });
            Theme::reTheme();
        }
        $dataParams = $data;
        $data = apply_filters(PLATFORM_DO_COMPONENT, $dataParams);
        if ($data !== $dataParams) return array_merge($data, ['csrf_token' => csrf_token()]);

        if (isset($param['type']) && ($param['type'] === 'livewire' || $param['type'] === '') && isset($param['component']) && $param['component']) {
            $route = Route::current();
            $params = isset($param['params']) ? $param['params'] : [];
            foreach ($params as $key => $value) {
                $route->setParameter($key, $value);
            }
            $html = '';
            $error_code = 0;
            try {
                $html =  Livewire::mount($param['component'], $params)->html();
            } catch (\Exception $ex) {
                $html = $ex->getMessage();
                $error_code = 500;
            }
            return [
                'html' =>  $html,
                'error_code' => $error_code,
                'param' => $param
            ];
        }
        if (!is_array($data) && $data !== $dataParams) {
            $data = ['data' => $data];
        }
        if ($data) {
            try {
                $params = isset($data['params']) ? $data['params'] : [];
                if (isset($data['view']) && $view = $data['view']) {
                    return [
                        'html' => view($view, $params)->render(),
                        'error_code' => 0,
                    ];
                }
            } catch (\Exception $ex) {
                return ['html' => '<div>not found</div>', 'data' => $data, 'error' => $ex, 'error_code' => 500, 'csrf_token' => csrf_token()];
            }
        }
        return ['html' => '<div>not found</div>', 'data' => $data, 'error' => 'not found', 'error_code' => 404, 'csrf_token' => csrf_token()];
    }
    public function doEvents(Request $request)
    {
        $event_name = $request->get('event');
        if ($event_name)
            return apply_filters(PLATFORM_DO_EVENT . '_' . Str::upper($event_name), ['request' => $request]);
        return  apply_filters(PLATFORM_DO_EVENT, ['request' => $request]);
    }
    public function doWebhooks(Request $request)
    {
        $webhook_name = $request->get('webhook');
        if ($webhook_name)
            return apply_filters(PLATFORM_DO_WEBHOOK . '_' . Str::upper($webhook_name), ['request' => $request]);

        return  apply_filters(PLATFORM_DO_WEBHOOK, ['request' => $request]);
    }
}
