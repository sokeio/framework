<?php

namespace Sokeio\Concerns;

use Illuminate\Support\Facades\Crypt;
use Sokeio\LivewireLoader;
use Livewire\Features\SupportPageComponents\PageComponentConfig;
use Livewire\Features\SupportPageComponents\SupportPageComponents;
use Sokeio\Facades\Platform;

trait WithLivewire
{
    use WithHelpers;
    public $soDataTemp = [];
    public $soRefId;
    public $soIsPage = false;
    public $soIsAdmin = false;
    public $soNumberLoading = 0;
    public function currentIsPage()
    {
        return $this->soIsPage;
    }
    protected function getListeners()
    {
        return [
            'refreshData' => 'soLoadData',
            'refreshData' . $this->getId() => 'soLoadData',
            'refreshData' . LivewireLoader::getNameByClass(get_called_class()) => 'soLoadData'
        ];
    }

    public function soLoadData()
    {
        $this->soNumberLoading++;
    }

    public function refreshData($option = [])
    {
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
        $this->callFuncById($this->soRefId, $func, $params);
    }
    public function refreshRefComponent()
    {
        $this->refreshData([
            'id' => $this->soRefId,
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
        return redirect(request()->header('Referer'));
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
        $this->soIsAdmin = Platform::keyAdmin();
        if (!$this->soRefId) {
            $this->soRefId = request('refComponent');
        }
    }

    public function __invoke()
    {
        // Here's we're hooking into the "__invoke" method being called on a component.
        // This way, users can pass Livewire components into Routes as if they were
        // simple invokable controllers. Ex: Route::get('...', SomeLivewireComponent::class);
        $html = '';
        //NOSONAR
        $layoutConfig = SupportPageComponents::interceptTheRenderOfTheComponentAndRetreiveTheLayoutConfiguration(function () use (&$html) {
            $params = SupportPageComponents::gatherMountMethodParamsFromRouteParameters($this);
            $html = app('livewire')->mount($this::class, [...$params, 'soIsPage' => request()->isMethod('get')]);
        });

        $layoutConfig = $layoutConfig ?: new PageComponentConfig();

        $layoutConfig->normalizeViewNameAndParamsForBladeComponents();

        $layoutConfig->view = themeLayout();
        $layoutConfig->slotOrSection = 'content';
        $layoutConfig->type = 'themeLayout';

        return SupportPageComponents::renderContentsIntoLayout($html, $layoutConfig);
    }
}
