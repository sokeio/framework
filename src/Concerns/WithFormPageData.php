<?php

namespace BytePlatform\Concerns;

trait WithFormPageData
{
    use WithFormData;
    public function getView()
    {
        return 'byte::forms.page';
    }
    public function render()
    {
        page_title($this->getItemManager()?->getTitle(),true);
        return view('byte::forms.page', [
            'itemManager' => $this->getItemManager()
        ]);
    }
}
