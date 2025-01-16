<?php

namespace Sokeio\Livewire\Concerns;

use Livewire\Features\SupportPageComponents\PageComponentConfig;
use Livewire\Features\SupportPageComponents\SupportPageComponents;
use Sokeio\Livewire\PageConfig;
use Sokeio\Attribute\PageInfo;

trait WithLivewirePage
{
    private $pageConfig = null;

    public function getPageConfig()
    {
        if (!$this->pageConfig) {
            $this->pageConfig = (new PageConfig($this))
                ->tap(
                    fn(PageConfig $pageConfig) =>
                    $pageConfig->setInfo(PageInfo::fromClass($this))
                );

            static::pageSetup($this->pageConfig);
        }
        return $this->pageConfig;
    }
    public static function pageSetup(PageConfig $pageConfig)
    {
        //TODO: Implement pageSetup
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
                $html = app('livewire')->mount($this::class, [
                    ...$params,
                    'soData' => [
                        'isPage' => request()->isMethod('get'),
                        'routeName' => request()->route()->getName(),
                    ]
                ]);
            }
        );

        $layoutConfig = $layoutConfig ?: new PageComponentConfig();

        $layoutConfig->normalizeViewNameAndParamsForBladeComponents();
        $layoutConfig->slotOrSection = 'content';
        $layoutConfig->type = 'themeLayout';
        PageConfig::setupLayout($this, $layoutConfig);
        return SupportPageComponents::renderContentsIntoLayout($html, $layoutConfig);
    }
}
