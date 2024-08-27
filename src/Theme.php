<?php

namespace Sokeio;

use Illuminate\Support\Facades\Facade;
// public function title($title)
// {
//     $this->title = $title;
// }
// public function description($description)
// {
//     $this->description = $description;
// }
// public function keywords($keywords)
// {
//     $this->keywords = $keywords;
// }
// public function enableCdn()
// {
//     $this->useCdn = true;
//     return $this;
// }
// public function disableCdn()
// {
//     $this->useCdn = false;
//     return $this;
// }
// public function js($content)
// {
//     $content = trim($content);
//     $key = md5($content);
//     $this->arrJavascript[$key] = $content;
//     return $this;
// }
// public function style($content)
// {
//     $content = trim($content);
//     $key = md5($content);
//     $this->arrStyle[$key] = $content;
//     return $this;
// }
// public function linkCss($link, $cdn = null)
// {
//     $this->arrLinkCss[trim($link)] = $cdn;
//     return $this;
// }
// public function linkJs($link, $cdn = null)
// {
//     $this->arrLinkJs[trim($link)] = $cdn;
//     return $this;
// }
// private function linkRender($link)
// {
//     echo '<link rel="stylesheet" href="' . ($link) . '">';
// }
// public function headRender()
// {
//     if ($this->title) {
//         echo '<title>' . $this->title . '</title>';
//     }
//     if ($this->description) {
//         echo '<meta name="description" content="' . $this->description . '">';
//     }
//     if ($this->keywords) {
//         echo '<meta name="keywords" content="' . $this->keywords . '">';
//     }

//     if ($this->useCdn) {
//         foreach ($this->arrLinkCss as $link => $cdn) {
//             if ($cdn) {
//                 $this->linkRender($cdn);
//             } else {
//                 $this->linkRender($link);
//             }
//         }
//     } else {
//         foreach ($this->arrLinkCss as $link => $cdn) {
//             $this->linkRender($link);
//         }
//     }
//     foreach ($this->arrStyle as $key => $value) {
//         echo '<style type="text/css" id="' . $key . '">' . $value . '</style>';
//     }
// }
// public function bodyRender()
// {
//     foreach ($this->bodyBefore as $callback) {
//         $callback();
//     }
// }

// private function jsRender($script)
// {
//     echo '<script type="text/javascript" src="' . ($script) . '"></script>';
// }
// public function bodyEndRender()
// {
//     if ($this->useCdn) {
//         foreach ($this->arrLinkJs as $link => $cdn) {
//             if ($cdn) {
//                 $this->jsRender($cdn);
//             } else {
//                 $this->jsRender($link);
//             }
//         }
//     } else {
//         foreach ($this->arrLinkJs as $link => $cdn) {
//             $this->jsRender($link);
//         }
//     }
//     foreach ($this->arrJavascript as $key => $value) {
//         echo '<script type="text/javascript" id="' . $key . '">' . $value . '</script>';
//     }

//     foreach ($this->bodyAfter as $callback) {
//         $callback();
//     }
// }
// public function bodyBefore($callback)
// {
//     if (!is_callable($callback)) {
//         return;
//     }
//     $this->bodyBefore[] = $callback;
// }
// public function bodyAfter($callback)
// {
//     if (!is_callable($callback)) {
//         return;
//     }
//     $this->bodyAfter[] = $callback;
// }
/**
 * @see \Sokeio\Theme
 * @method static void title($title);
 * @method static void description($description);
 * @method static void keywords($keywords);
 * @method static void enableCdn();
 * @method static void disableCdn();
 * @method static void js($content);
 * @method static void style($content);
 * @method static void linkCss($link, $cdn = null);
 * @method static void linkJs($link, $cdn = null);
 * @method static void bodyBefore($callback);
 * @method static void bodyAfter($callback);
 * @method static void headBefore($callback);
 * @method static void headAfter($callback);
 * @method static void headRender()
 * @method static void bodyRender()
 * @method static void bodyEndRender()
 *
 */
class Theme extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sokeio_theme';
    }
}
