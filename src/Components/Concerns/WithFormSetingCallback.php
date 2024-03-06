<?php

namespace Sokeio\Components\Concerns;


trait WithFormSetingCallback
{
    use  WithForm {
        boot as parentBoot;
    }

    public $eventCallback = '';
    protected function KeyBase64Settings()
    {
        return [];
    }
    public function getDataCallback()
    {
        return [];
    }
    public function boot()
    {
        $form = request('___setting_data');
        if ($form) {
            foreach ($form as $key => $value) {
                if (in_array($key, $this->KeyBase64Settings())) {
                    $this->data->{$key} = $this->Base64Decode($value);
                } else {
                    $this->data->{$key} = $value;
                }
            }
        }
        $this->eventCallback = request('___setting_callback_event') ?? $this->eventCallback;
        if (!$this->eventCallback)  $this->eventCallback = 'testCallback';
        $this->parentBoot();
        
    }
}
