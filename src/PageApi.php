<?php

namespace Sokeio;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Sokeio\Platform\ItemInfo;

abstract class PageApi  implements IPage
{
    public static function Json($data, $code = 200, $message = null, $errors = null, $headers = [], $meta = [])
    {
        return response()->json([
            'data' => $data,
            'code' => $code,
            'message' => $message,
            'errors' => $errors,
            'meta' => $meta,
            'version' => Platform::version(),
            'timestamp' => time()
        ], $code, $headers);
    }
    abstract public function action();
    public static function pageAuth()
    {
        return false;
    }
    public static function pageName()
    {

        return null;
    }
    public static function pageUrl()
    {

        return null;
    }
    public static function runLoad(ItemInfo $itemInfo)
    {
        $classMe = static::class;
        $namespacePage = $itemInfo->namespace . '\\Pages\\';
        // do nothing
        $url = str($classMe)->after($namespacePage);
        $urlRoute = static::pageUrl() ?? str($url)->split('/\\\\/', -1, PREG_SPLIT_NO_EMPTY)
            ->map([Str::class, 'kebab'])->join('/');
        $nameRoute = static::pageName() ??  ($itemInfo
            ->getPackage()
            ->shortName() . '-page.' . str($urlRoute)->replace('/', '.'));
        Platform::routeApi(function () use ($classMe, $urlRoute, $nameRoute) {
            Route::get($urlRoute, $classMe)->name($nameRoute);
        }, !static::pageAuth());
    }
    public function __invoke()
    {
        return call_user_func([$this, 'action']);
    }
}
