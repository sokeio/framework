<?php

namespace Sokeio\Support\Livewire\Concerns;

use Illuminate\Support\Facades\Request;
use Livewire\Attributes\Locked;
use Sokeio\Concerns\WithHelpers;

trait WithLivewire
{
    use WithHelpers, WithLivewireData, WithLivewireDispatch;

    public $soNumberLoading = 0;
    #[Locked]
    public $isPageAjax = false;

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
        if (!$this->isPageAjax) {
            $this->isPageAjax = Request::ajax();
        }
    }
}
