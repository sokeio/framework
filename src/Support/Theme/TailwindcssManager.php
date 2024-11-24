<?php

namespace Sokeio\Support\Theme;

use Illuminate\Support\Facades\Cache;

class TailwindcssManager
{
    public function render($theme)
    {
        if (!data_get($theme, 'tailwindcss.enable', false)) {
            return;
        }
        $tailwindcss = data_get($theme, 'tailwindcss');
        $plugins = data_get($tailwindcss, 'plugins', []);
        $cdn = data_get($tailwindcss, 'cdn', 'https://cdn.tailwindcss.com');
        $inline = data_get($tailwindcss, 'inline', false);
        $obfuscator = data_get($tailwindcss, 'obfuscator', false);
        $cdn = $cdn . '?plugins=' . implode(',', $plugins);
        if ($inline) {
            // get content from cdn and cache 7 days
            $key = md5($cdn);
            if (!Cache::has($key)) {
                $cdn = file_get_contents($cdn);
                Cache::set($key, $cdn, 30 * 24 * 60 * 60);
            } else {
                $cdn = Cache::get($key);
            }
            if ($obfuscator) {
                //TODO: obfuscator js
            }
            echo "<script>{$cdn}</script>";
            return;
        }
        echo "<script src='{$cdn}'></script>";
    }
}
