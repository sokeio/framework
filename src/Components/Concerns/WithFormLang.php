<?php

namespace Sokeio\Components\Concerns;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;

trait WithFormLang
{
    public $lang;
    protected function loadDataBefore($data)
    {
        if (!$data->author_id) {
            $data->author_id = auth()->user()->id;
        }
        if ($this->lang) {
            $data->setDefaultLocale($this->lang);
            $data->locale = $this->lang;
        }
    }
    public function bootWithFormLang()
    {
        if (!$this->lang) {
            $this->lang = request('lang');
        }
    }
    public function changeLangeuage($lang)
    {
        $this->lang = $lang;
        $this->loadData();
        $this->refreshMe();
    }
}
