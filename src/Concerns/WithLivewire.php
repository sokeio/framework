<?php

namespace Sokeio\Concerns;

use Sokeio\LivewireLoader;
use Livewire\Features\SupportPageComponents\PageComponentConfig;
use Livewire\Features\SupportPageComponents\SupportPageComponents;

trait WithLivewire
{
    use WithHelpers;
    public $_dataTemps = [];
    public $_refComponentId;
    public $___isPage = false;
    public $___theme___admin = false;
    public $___number_loading = 0;
    public function CurrentIsPage()
    {
        return $this->___isPage;
    }
    protected function getListeners()
    {
        return [
            'refreshData' . $this->getId() => '__loadData', 'refreshData' => '__loadData', 'refreshData' . LivewireLoader::getNameByClass(get_called_class()) => '__loadData'
        ];
    }

    public function __loadData()
    {
        $this->___number_loading++;
    }

    public function refreshData($option = [])
    {
        // if (!isset($option['id']) && !count($option)) $option['id'] = $this->getId();
        $this->dispatch('sokeio::refresh', option: $option);
    }

    public function callFunc($option = [])
    {
        $this->dispatch('sokeio::call', option: $option);
    }
    public function callFuncByName($name, $func, $params = [])
    {
        $this->callFunc([
            'component' => $name,
            'func' => $func,
            'params' => $params
        ]);
    }
    public function callFuncById($id, $func, $params = [])
    {
        $this->callFunc([
            'id' => $id,
            'func' => $func,
            'params' => $params
        ]);
    }
    public function callFuncByRef($func, $params = [])
    {
        return $this->callFuncById($this->_refComponentId, $func, $params);
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
        $this->dispatch('sokeio::message', option: $option);
    }
    public function closeComponent($component = null)
    {
        if ($component) {
            $this->dispatch('sokeio::close',  option: ['component' => $component]);
        } else {

            $this->dispatch('sokeio::close',  option: ['id' => $this->getId()]);
        }
    }
    public function booted()
    {
        if (function_exists('sokeio_is_admin')) {
            $this->___theme___admin = sokeio_is_admin();
        }
        if (!$this->_refComponentId) {
            $this->_refComponentId = request('refComponent');
        }
    }

    public function __invoke()
    {
        // Here's we're hooking into the "__invoke" method being called on a component.
        // This way, users can pass Livewire components into Routes as if they were
        // simple invokable controllers. Ex: Route::get('...', SomeLivewireComponent::class);
        $html = null;

        $layoutConfig = SupportPageComponents::interceptTheRenderOfTheComponentAndRetreiveTheLayoutConfiguration(function () use (&$html) {
            $params = SupportPageComponents::gatherMountMethodParamsFromRouteParameters($this);
            $html = app('livewire')->mount($this::class, [...$params, '___isPage' => request()->isMethod('get')]);
        });

        $layoutConfig = $layoutConfig ?: new PageComponentConfig();

        $layoutConfig->normalizeViewNameAndParamsForBladeComponents();

        $layoutConfig->view = theme_layout();
        $layoutConfig->slotOrSection = 'content';
        $layoutConfig->type = 'themeLayout';

        return SupportPageComponents::renderContentsIntoLayout($html, $layoutConfig);
    }
}
