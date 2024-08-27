<?php

namespace Sokeio\Platform\Loader;

use Closure;
use Sokeio\LivewireLoader;
use Sokeio\Platform\ILoader;
use Sokeio\Platform\ItemInfo;

class PageLoader implements ILoader
{
    public function handle(ItemInfo $data, Closure $next): mixed
    {
        LivewireLoader::register(
            $data->getPackage()->basePath('Pages'),
            $data->namespace . '\\Pages',
            $data->getPackage()->shortName() . '::pages',
            function ($class) use ($data) {
                call_user_func([$class, 'runLoad'], $data);
                return $class;
            }
        );
        return $next($data);
    }
}
