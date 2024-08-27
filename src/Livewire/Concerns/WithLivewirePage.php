<?php

namespace Sokeio\Livewire\Concerns;

use Illuminate\Support\Facades\View;
use Livewire\Features\SupportPageComponents\PageComponentConfig;
use Livewire\Features\SupportPageComponents\SupportPageComponents;

trait WithLivewirePage
{
    protected static function isThemeAdmin()
    {
        return false;
    }
    protected static function themeLayout($page)
    {
        return null;
    }
    protected function themePage()
    {
        $layout = null;
        if ($layout = static::themeLayout($this) && View::exists($layout)) {
            return $layout;
        }
        return 'sokeio::layouts.none';
    }
    public function __invoke()
    {
        // Here's we're hooking into the "__invoke" method being called on a component.
        // This way, users can pass Livewire components into Routes as if they were
        // simple invokable controllers. Ex: Route::get('...', SomeLivewireComponent::class);
        $html = '';
        $layoutConfig = SupportPageComponents::interceptTheRenderOfTheComponentAndRetreiveTheLayoutConfiguration(
            function () use (&$html) {
                $params = SupportPageComponents::gatherMountMethodParamsFromRouteParameters($this);
                $html = app('livewire')->mount($this::class, [...$params, 'soIsPage' => request()->isMethod('get')]);
            }
        );

        $layoutConfig = $layoutConfig ?: new PageComponentConfig();

        $layoutConfig->normalizeViewNameAndParamsForBladeComponents();

        $layoutConfig->view = $this->themePage();
        $layoutConfig->slotOrSection = 'content';
        $layoutConfig->type = 'themeLayout';

        return SupportPageComponents::renderContentsIntoLayout($html, $layoutConfig);
    }
}
