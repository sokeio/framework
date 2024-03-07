<?php

namespace Sokeio\Livewire;

use Sokeio\Components\FormSetting;
use Sokeio\Components\UI;
use Sokeio\Models\Permalink;
use Sokeio\Platform\PermalinkManager;

class PermalinkSetting extends FormSetting
{
    protected function getTitle()
    {
        return __('Permalink Setting');
    }
    public function SettingUI()
    {
        return UI::forEach(PermalinkManager::getDefault(), UI::text(function ($item) {
            return $item->getEachKey();
        })->valueDefault(function ($item) {
            return  $item->getEachData();
        })->label(function ($item) {
            return str_replace(['permalink', '_'], ' ', $item->getEachKey());
        }));
    }
    protected function LoadSetting($keyForm, $keyValue, $column)
    {
        $value = PermalinkManager::getPermalink($column->getName(), $column->getValueDefault())[0]['value'];
        data_set($this, $keyForm, $value);
        return $this;
    }
    protected function SaveSetting($keyForm, $keyValue, $column)
    {
        Permalink::updateOrCreate(
            ['key' =>  $keyValue],
            [
                'lang' => '',
                'value' => data_get($this, $keyForm),
                'status' => true,
            ]
        );
        return $this;
    }
}
