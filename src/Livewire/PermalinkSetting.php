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
        return UI::ForEach(PermalinkManager::getDefault(), UI::Text(function ($item) {
            return $item->getEachKey();
        })->ValueDefault(function ($item) {
            return  $item->getEachData();
        })->Label(function ($item) {
            return str_replace(['permalink', '_'], ' ', $item->getEachKey());
        }));
    }
    protected function LoadSetting($keyForm, $keyValue, $column)
    {
        data_set($this, $keyForm, PermalinkManager::getPermalink($column->getName(), $column->getValueDefault())[0]['value']);
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
