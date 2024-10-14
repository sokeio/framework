<?php

namespace Sokeio\Support\Livewire\Concerns;

use Livewire\Features\SupportPageComponents\PageComponentConfig;
use Livewire\Features\SupportPageComponents\SupportPageComponents;
use Sokeio\Support\Livewire\PageConfig;
use Sokeio\Support\Livewire\PageInfo;
trait WithLivewirePage
{
    private $pageConfig = null;
    public function getPageConfig()
    {
        if (!$this->pageConfig) {
            $this->pageConfig = new PageConfig($this);
            // use ReflectionClass
            $reflection = new \ReflectionClass(get_class($this));
            // getAttributes  PageInfo::class
            $attributes = $reflection->getAttributes(PageInfo::class, \ReflectionAttribute::IS_INSTANCEOF);
            foreach ($attributes as $attribute) {
                $this->pageConfig->setInfo($attribute->newInstance());
            }
            $this->pageSetup($this->pageConfig);
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
                $html = app('livewire')->mount($this::class, [...$params, 'soIsPage' => request()->isMethod('get')]);
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
