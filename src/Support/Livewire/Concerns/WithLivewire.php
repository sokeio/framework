<?php

namespace Sokeio\Support\Livewire\Concerns;

use Sokeio\Concerns\WithHelpers;

trait WithLivewire
{
    use WithHelpers, WithLivewireData, WithLivewireDispatch;

    public $soNumberLoading = 0;

    public function soLoadData()
    {
        $this->soNumberLoading++;
    }
    protected function getListeners()
    {
        return [
            'refreshData' => 'soLoadData',
            'refreshData' . $this->getId() => 'soLoadData'
        ];
    }

    // public function refreshData($option = [])
    // {
    //     $this->dispatch('sokeio::refresh', option: $option);
    // }

    // public function callFunc($option = [])
    // {
    //     $this->dispatch('sokeio::call', option: $option);
    // }
    // public function callFuncByName($name, $func, $params = [])
    // {
    //     $this->callFunc([
    //         'component' => $name,
    //         'func' => $func,
    //         'params' => $params
    //     ]);
    // }
    // public function callFuncById($id, $func, $params = [])
    // {
    //     $this->callFunc([
    //         'id' => $id,
    //         'func' => $func,
    //         'params' => $params
    //     ]);
    // }
    // public function callFuncByRef($func, $params = [])
    // {
    //     $this->callFuncById($this->soRefId, $func, $params);
    // }
    // public function refreshRefComponent()
    // {
    //     $this->refreshData([
    //         'id' => $this->soRefId,
    //     ]);
    // }
    // public function refreshMe()
    // {
    //     $this->refreshData([
    //         'id' => $this->getId(),
    //     ]);
    // }
    // public function refreshComponent($component)
    // {
    //     $this->refreshData([
    //         'component' => LivewireLoader::getNameComponent($component),
    //     ]);
    // }

    // public function closeComponent($component = null)
    // {
    //     if ($component) {
    //         $this->dispatch('sokeio::close',  option: ['component' => $component]);
    //     } else {
    //         $this->dispatch('sokeio::close',  option: ['id' => $this->getId()]);
    //     }
    // }
    public function redirectCurrent()
    {
        return redirect(request()->header('Referer'));
    }
    public function booted()
    {
        if (!$this->getRefId()) {
            $this->data('refId', request('refId'));
        }
    }
}
