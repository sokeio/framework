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
