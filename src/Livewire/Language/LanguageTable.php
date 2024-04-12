<?php

namespace Sokeio\Livewire\Language;

use Sokeio\Components\Table;
use Sokeio\Components\UI;
use Sokeio\Models\Language;

class LanguageTable extends Table
{
    protected function getModel()
    {
        return Language::class;
    }
    public function getTitle()
    {
        return __('Language');
    }
    protected function getRoute()
    {
        return 'admin.system.language';
    }

    public function doChangeStatus($id, $status)
    {
        $this->getQuery()->where('id', $id)->update(['status' => $status]);
    }
    public function getColumns()
    {
        return [
            UI::text('name')->label(__('Name')),
            UI::text('code')->label(__('Code')),
            UI::text('flag')->label(__('Flag')),
            UI::button('status')->label(__('Status'))->NoSort()->wireClick(function ($item) {
                if ($item->getDataItem()->status) {
                    $item->title(__('Active'));
                    $item->primary();
                } else {
                    $item->title(__('Block'));
                    $item->warning();
                }
                return 'doChangeStatus(' . $item->getDataItem()->id . ',' . ($item->getDataItem()->status ? 0 : 1) . ')';
            })
        ];
    }
}
