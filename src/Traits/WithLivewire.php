<?php

namespace BytePlatform\Traits;

use BytePlatform\LivewireLoader;
use Livewire\Features\SupportPageComponents\LayoutConfig;
use Livewire\Features\SupportPageComponents\SupportPageComponents;

trait WithLivewire
{
    use WithHelpers;
    public $_dataTemps = [];
    public $_refComponentId;
    public $__method_get_first = false;
    public $___theme___admin = false;
    public $__number_loading = 0;
    protected function getListeners()
    {
        return [
            'refreshData' . $this->getId() => '__loadData', 'refreshData' => '__loadData', 'refreshData' . LivewireLoader::getNameByClass(get_called_class()) => '__loadData'
        ];
    }

    public function __loadData()
    {
        $this->__number_loading++;
    }

    public function refreshData($option = [])
    {
        // if (!isset($option['id']) && !count($option)) $option['id'] = $this->getId();
        $this->dispatch('byte::refresh', option: $option);
    }
    public function refreshRefComponent()
    {
        $this->refreshData([
            'id' => $this->_refComponentId,
        ]);
    }
    public function refreshMe()
    {
        $this->refreshData([
            'id' => $this->getId(),
        ]);
    }
    public function refreshComponent($component)
    {
        $this->refreshData([
            'component' => LivewireLoader::getNameComponent($component),
        ]);
    }
    public function redirectCurrent()
    {
        return redirect(request()->header('Referer'));;
    }
    public function showMessage($option)
    {
        $this->dispatch('byte::message', option: $option);
    }
    public function closeComponent($component = null)
    {
        if ($component) {
            $this->dispatch('byte::close',  option: ['component' => $component]);
        } else {

            $this->dispatch('byte::close',  option: ['id' => $this->getId()]);
        }
    }
    public function booted()
    {
        if (function_exists('byte_is_admin')) {
            $this->___theme___admin = byte_is_admin();
        }
        if (!$this->_refComponentId) {
            $this->_refComponentId = request('refComponent');
        }
    }
    // protected function ensureViewHasValidLivewireLayout($view)
    // {
    //     if ($view == null) {
    //         return;
    //     }
    //     parent::ensureViewHasValidLivewireLayout($view);
    //     $view->extends(theme_layout())->section('content');
    // }
    public  function __invoke()
    {

        // Here's we're hooking into the "__invoke" method being called on a component.
        // This way, users can pass Livewire components into Routes as if they were
        // simple invokable controllers. Ex: Route::get('...', SomeLivewireComponent::class);
        $html = null;

        $layoutConfig = SupportPageComponents::interceptTheRenderOfTheComponentAndRetreiveTheLayoutConfiguration(function () use (&$html) {
            $params = SupportPageComponents::gatherMountMethodParamsFromRouteParameters($this);

            $html = app('livewire')->mount($this::class, $params);
        });

        $layoutConfig = $layoutConfig ?: new LayoutConfig();

        $layoutConfig->normalizeViewNameAndParamsForBladeComponents();

        $layoutConfig->view = theme_layout();
        $layoutConfig->slotOrSection = 'content';
        $layoutConfig->type = 'themeLayout';

        return SupportPageComponents::renderContentsIntoLayout($html, $layoutConfig);
    }
}
