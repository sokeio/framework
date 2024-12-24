<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Sokeio\Support\Theme\ThemeParser;

return [
    'themeBodyEnd' => function () {
        return <<<EOT
        <?php
            \Sokeio\Theme::bodyEndRender();
        ?>
        EOT;
    },
    'themeBody' => function () {
        return <<<EOT
        <?php
            \Sokeio\Theme::bodyRender();
        ?>
        EOT;
    },
    'themeBodyAttr' => function () {
        return <<<EOT
        <?php
            \Sokeio\Theme::bodyAttrRender();
        ?>
        EOT;
    },
    'themeHead' => function () {
        return <<<EOT
        <?php
            \Sokeio\Theme::headRender();
        ?>
        EOT;
    },
    'themeInclude' => function ($expression) {
        return <<<EOT
        <?php
            \Sokeio\Theme::include({$expression});
        ?>
        EOT;
    },
    'themeLocation' => function ($expression) {
        return <<<EOT
        <?php
            \Sokeio\Theme::renderLocation({$expression});
        ?>
        EOT;
    },
    'viewjs' => function ($expression) {
        return <<<EOT
        <?php
            echo viewjs({$expression});
        ?>
        EOT;
    },
    'sokeio' => function ($expression) {
        $expression = ThemeParser::multipleArgs($expression);
        $viewBasePath = Blade::getPath();
        foreach ($this->app['config']['view.paths'] as $path) {
            if (substr($viewBasePath, 0, strlen($path)) === $path) {
                $viewBasePath = substr($viewBasePath, strlen($path));
                break;
            }
        }
        $template = trim($expression[0], '\/');
        $template = trim($template, '"');
        $template = trim($template, '\'');
        $viewBasePath = '/' . dirname(trim($viewBasePath, '\/'));
        $content = '';
        if (File::exists($viewBasePath . '/' . $template)) {
            $content = file_get_contents($viewBasePath . '/' . $template);
        }
        $attr = [];
        if (isset($expression[1])) {
            $attr = eval('return ' . $expression[1] . ';');
        }
        $attrText = '';
        foreach ($attr as $key => $value) {
            $attrText .= ' ' . $key . '="' . trim($value)  . '"';
        }
        $attrWrapper = [];
        if (isset($expression[2])) {
            $attrWrapper = eval('return ' . $expression[2] . ';');
        }
        if (isset($attrWrapper['wire:ignore'])) {
            unset($attrWrapper['wire:ignore']);
        }
        if (!isset($attrWrapper['class'])) {
            $attrWrapper['class'] = '';
        }
        $attrWrapper['class'] .= ' sokeio-template';
        $attrWrapperText = '';
        foreach ($attrWrapper as $key => $value) {
            $attrWrapperText .= ' ' . $key . '="' . trim($value) . '"';
        }
        return <<<EOT
        <div wire:ignore {$attrWrapperText}>
            <template wire:sokeio {$attrText}>
                {$content}
            </template>
        </div>
        EOT;
    },
    /*
    |---------------------------------------------------------------------
    | @count
    |---------------------------------------------------------------------
    |
    | Usage: @count([1,2,3])
    |
    */

    'count' => function ($expression) {
        return '<?php echo ' . count(json_decode($expression)) . '; ?>';
    },

    /*
    |---------------------------------------------------------------------
    | @nl2br
    |---------------------------------------------------------------------
    */

    'nl2br' => function ($expression) {
        return "<?php echo nl2br($expression); ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @kebab, @snake, @camel
    |---------------------------------------------------------------------
    */

    'kebab' => function ($expression) {
        return '<?php echo ' . Str::kebab($expression) . '; ?>';
    },

    'snake' => function ($expression) {
        return '<?php echo ' . Str::snake($expression) . '; ?>';
    },

    'camel' => function ($expression) {
        return '<?php echo ' . Str::camel($expression) . '; ?>';
    },
];
