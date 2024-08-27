<?php

namespace Sokeio\Livewire\Concerns;

use Sokeio\LivewireLoader;
use Sokeio\Concerns\WithHelpers;
use Sokeio\Facades\Platform;

trait WithLivewire
{
    use WithHelpers, WithLivewireMessage;
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
            'refreshData' . $this->getId() => 'soLoadData'
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
        if (!$this->soRefId) {
            $this->soRefId = request('refComponent');
        }
    }
}
