<?php

namespace Sokeio\Livewire\Concerns;

use Livewire\Features\SupportPageComponents\PageComponentConfig;
use Livewire\Features\SupportPageComponents\SupportPageComponents;
use Sokeio\Theme;

trait WithLivewirePage
{
    public static function pageUrl()
    {
        return null;
    }
    public static function pageName()
    {
        return null;
    }
    public static function menuTitle()
    {
        return null;
    }
    public static function pageAdmin()
    {
        return false;
    }

    public static function pageAuth()
    {
        return false;
    }
    protected function pageTitle()
    {
        return null;
    }
    public static function pageIcon()
    {
        return null;
    }
    protected function themePage()
    {
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

        $layoutConfig->view = Theme::getLayout($this->themePage());
        $layoutConfig->slotOrSection = 'content';
        $layoutConfig->type = 'themeLayout';
        Theme::title($this->pageTitle());
        return SupportPageComponents::renderContentsIntoLayout($html, $layoutConfig);
    }
}
