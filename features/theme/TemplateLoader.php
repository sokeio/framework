<?php

namespace Sokeio\Theme;

use Closure;
use Illuminate\Support\Facades\File;
use Sokeio\Content\Template;
use Sokeio\Core\IPipeLoader;
use Sokeio\Core\ItemInfo;

class TemplateLoader implements IPipeLoader
{
    public function handle(ItemInfo $item, Closure $next): mixed
    {
        $name = $item->getPackage()->shortName();
        $path = $item->getPackage()->basePath('../resources/views/template');
        if (File::exists($path)) {
            foreach (File::allFiles($path) as $file) {
                // remove the .blade.php and remove the base path
                $path = trim(str_replace($path, '', $file->getPathname()), '/');
                $path = str_replace('.blade.php', '', $path);
                $path = str_replace('/', '.', $path);
                Template::register($name . '::template.' . $path, $file->getContents());
            }
        }

        return $next($item);
    }
}
